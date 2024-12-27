<?php

namespace App\Livewire\WorkTripInfos;

use App\Models\WorkTripInfo;
use App\Repositories\Contracts\IDBRepository;
use App\Repositories\Contracts\IPostRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\IWorkTripRepository;
use App\Utils\Constants;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

class Import extends Component
{
    use WithPagination, WithFileUploads;
    protected IDBRepository $dbRepos;
    protected IUserRepository $usrRepos;
    protected IPostRepository $pstRepos;
    protected IWorkTripRepository $wtRepos;
    public array $authUsr, $columns;

    #[Validate('required|file|mimes:csv,txt')]
    public ?UploadedFile $csvFile = null;

    #[Layout('layouts.app')]
    public function render(): View
    {
        $wellMasters = WorkTripInfo::paginate();

        return view('livewire.work-trip-info.import', compact('wellMasters'))
            ->with('i', $this->getPage() * $wellMasters->perPage());
    }

    public function boot(
        IDBRepository $dbRepos,
        IUserRepository $usrRepos,
        IPostRepository $pstRepos,
        IWorkTripRepository $wtRepos): void
    {
        $this->dbRepos = $dbRepos;
        $this->usrRepos = $usrRepos;
        $this->pstRepos = $pstRepos;
        $this->wtRepos = $wtRepos;
    }

    public function mount(): void
    {
        $this->initForm();
        $this->initAuthUser();
    }

    private function initForm(): void
    {
        $this->columns = [
            'date', 'time', 'act_name', 'act_process', 'act_unit', 'act_value', 'area_name', 'area_loc', 'post_id', 'user_id'
        ];
    }

    private function readCSV($csvFile, $delimiter = ','): ?array
    {
        $line_of_text = null;
        $file_handle = fopen($csvFile, 'r');
        while ($csvRow = fgetcsv($file_handle, null, $delimiter)) {
            $line_of_text[] = $csvRow;
        }
        fclose($file_handle);
        return $line_of_text;
    }

    private function removeBom($filePath): void
    {
        $content = file_get_contents($filePath);

        $bom = "\xEF\xBB\xBF"; // BOM UTF-8
        if (substr($content, 0, 3) === $bom) {
            $content = substr($content, 3);
            file_put_contents($filePath, $content);
        }
    }

    private function initAuthUser(): void
    {
        $this->authUsr = $this->usrRepos->authenticatedUser()->toArray();
    }

    public function import(): void
    {
        $this->validate();
        try {
            $this->dbRepos->async();
            $report = ['updated' => 0, 'added' => 0, 'batch' => 0];

            $tempPath = $this->csvFile->getRealPath();
            $this->removeBom($tempPath);
            $csv = $this->readCSV($tempPath);

            if (is_null($csv)) return;
            $workTripInfos = []; $dateAndPostIdState = [];

            foreach ($csv as $rawRow) {
                $rowInput = []; $row = explode(';', collect($rawRow)->first());

                foreach ($this->columns as $i => $col) {
                    $rowInput[$col] = $row[$i] ?? null;
                }
                $dateAndPostIdState[$row[0]] = null;
                $workTripInfos[] = $rowInput;
            }

            foreach ($workTripInfos as $info) {
                $matchRow = WorkTripInfo::query()
                    ->where('date', '=', $info['date'], 'and')
                    ->where('time', '=', $info['time'], 'and')
                    ->where('act_process', '=', $info['act_process'], 'and')
                    ->where('area_loc', $info['area_loc']);

                if ($matchRow->exists()) {
                    $existing = $matchRow->first()->toArray();
                    $existing['act_value'] = $info['act_value'];
                    $this->wtRepos->updateInfo($existing);
                    $report['updated']++;
                    continue;
                }
                if (is_null($dateAndPostIdState[$date = $info['date']])) {
                    $postId = $this->pstRepos->generatePost($this->authUsr, ['created_at' => $date]);
                    $dateAndPostIdState[$info['date']] = $postId
                        ?? throw new Exception('Trouble while generating new post');
                    $report['batch']++;
                }
                $info['post_id'] = $dateAndPostIdState[$info['date']];
                $info['user_id'] = $this->authUsr['id'];
                $this->wtRepos->addInfo($info);
                $report['added']++;
            }
            $message = 'Hasil eksekusi file yang anda upload adalah ';
            $message .= 'updated: ' . $report['updated'] .
                ', added: ' . $report['added'] .
                ', batch: ' . $report['batch'];
            session()->flash('message', $message);
            $this->dbRepos->await();

        } catch (\Throwable $exception) {
            $this->dbRepos->cancel();
            $message = $exception->getMessage();

            session()->flash('message', $message);
            Log::debug($message);
        }
    }
}
