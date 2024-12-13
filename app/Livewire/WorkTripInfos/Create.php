<?php

namespace App\Livewire\WorkTripInfos;

use App\Livewire\BaseComponent;
use App\Livewire\Forms\WorkTripInfoForm;
use App\Models\Activity;
use App\Models\WorkTripInfo;
use App\Repositories\Contracts\IDBRepository;
use App\Repositories\Contracts\IPostRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\IWorkTripRepository;
use App\Utils\ActNameEnum;
use App\Utils\Constants;
use App\Utils\Contracts\IUtility;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

class Create extends BaseComponent
{
    #[Url]
    public ?string $dateParam = null;

    protected IDBRepository $dbRepos;
    protected IUtility $util;
    protected IUserRepository $usrRepos;
    protected IPostRepository $pstRepos;
    protected IWorkTripRepository $wtRepos;

    public WorkTripInfoForm $form;
    public array
        $authUsr, $infoState, $locations,
        $dateOptions, $timeOptions,
        $datesRaw, $timesRaw, $delInfoQueue = [];
    public ?string $postId, $focusedDate;
    public bool $isEditMode = false;

    public function mount(
        WorkTripInfo $workTripInfo,
        IDBRepository $dbRepos,
        IUtility $util,
        IUserRepository $usrRepos,
        IPostRepository $pstRepos,
        IWorkTripRepository $wtRepos): void
    {
        $date = $this->dateParam;
        $this->isEditMode = !is_null($date);

        $this->dbRepos = $dbRepos;
        $this->util = $util;
        $this->usrRepos = $usrRepos;
        $this->pstRepos = $pstRepos;
        $this->wtRepos = $wtRepos;

        $this->form->setWorkTripInfoModel($workTripInfo);
        $this->initAuthUser();
        $this->initDate($date);
        $this->initTimeOptions(/*$date*/);
        $this->initLocOptions();
        $this->checkInfoState($date);
    }

    public function hydrate(
        IDBRepository $dbRepos,
        IUtility $util,
        IPostRepository $pstRepos,
        IWorkTripRepository $wtRepos): void
    {
        $this->dbRepos = $dbRepos;
        $this->util = $util;
        $this->pstRepos = $pstRepos;
        $this->wtRepos = $wtRepos;
    }

    private function checkInfoState($dateParam): void
    {
        $this->isEditMode = !is_null($dateParam);

        if ($this->isEditMode) {
            $this->getInfoState($dateParam);
            return;
        }
        $dateOrDates = str_contains($this->form->date, '~')
            ? $this->datesRaw : $this->form->date;

        $timeOrTimes = str_contains($this->form->time, '~')
            ? $this->timesRaw : $this->form->time;

        $areaName = $this->authUsr['area_name'];

        $areInfosExists = str_contains($this->form->time, '~')
            ? $this->wtRepos->areInfosExistByDateOrDatesAndArea($dateOrDates, $areaName)
            : $this->wtRepos->areInfosExistByDatetimeOrDatesTimeAndArea(
                $dateOrDates, $timeOrTimes, $areaName
            );
        if (!$areInfosExists) {
            $this->initInfoState();
            return;
        }
        if (is_string($dateOrDates)) {
            $this->getInfoState($dateOrDates);
            return;
        }
        $latestDate = $this->wtRepos
            ->getLatestInfosDateByDateOrDatesAndArea($dateOrDates, $areaName);

        $startDate = $this->util->addDaysOfParse($latestDate);

        $this->initDate(null, $startDate);
        $this->initInfoState();
    }

    /**
     * @throws \Exception
     */
    private function checkPostThenGenerate(): void
    {
        $dateOrDates = str_contains($this->form->date, '~')
            ? $this->datesRaw : $this->form->date;

        $timeOrTimes = str_contains($this->form->time, '~')
            ? $this->timesRaw : $this->form->time;

        $areActualTripsSubmitted = $this->wtRepos->areTripsExistByDatetimeOrDatesTimeAndArea(
            $dateOrDates, $timeOrTimes, $this->authUsr['area_name']
        );
        if ($areActualTripsSubmitted) {
            throw new \Exception('Sorry, your plan already used.');
        }
        $this->postId = $this->pstRepos->generatePost(
            $this->authUsr, ['created_at' => $this->focusedDate]
        );
    }

    /**
     * @throws \Exception
     */
    private function checkInfoDeletion(): void
    {
        if (empty($this->delInfoQueue)) return;
        $info = $this->delInfoQueue[0];

        if (str_contains($info['date'], '~')) {
            $info['date'] = $this->datesRaw;
        }
        $areTripsAlreadyExist = $this->wtRepos->areTripsExistByDatetimeAndArea(
            $info['date'], $info['time'], $info['area_name']
        );
        if ($areTripsAlreadyExist) {
            throw new \Exception('Failed, the plan already used by contractor.');
        }
        foreach ($this->delInfoQueue as $info) {
            $this->wtRepos->removeInfoById($info['id']);
        }
    }

    private function initDate(?string $initDate = null, ?string $startDate = null): void
    {
        $this->focusedDate = $startDate ?? date('Y-m-d');

        if (!is_null($initDate)) {
            $this->dateOptions[] = ['name' => $initDate, 'value', $initDate];
            return;
        }
        $this->dateOptions = $this->util->getListOfDatesOptions(
            Constants::DATE_COUNT, $startDate, true
        );
        $this->datesRaw = $this->util->getListOfDates(
            Constants::DATE_COUNT, $startDate
        );
        $this->form->date = $this->dateOptions[0]['value'] ?? '';
    }

    private function initTimeOptions(): void
    {
        $this->timesRaw = $this->util->getListOfTimes(Constants::TIME_START, Constants::TIME_END);
        $this->timeOptions = $this->util->getListOfTimesOptions(Constants::TIME_START, Constants::TIME_END, true);
        $this->form->time = $this->timeOptions[0]['value'] ?? '';
    }

    private function initAuthUser(): void
    {
        $this->authUsr = $this->usrRepos->authenticatedUser()->toArray();
    }

    private function initLocOptions(): void
    {
        $areaName = $this->authUsr['area_name'] ?? null;
        if (is_null($areaName)) return;

        $this->form->area_name = $areaName;
        $this->locations = $this->wtRepos->getLocations($areaName);
        // $this->form->area_loc = $this->locationOptions[0]['value'] ?? 'NA';
    }

    private function initInfoState(): void
    {
        if (!$this->isEditMode) {
            $this->infoState = array();
            $this->form->act_value = empty($this->form->act_value)
                ? 0 : $this->form->act_value;
        }
        $this->form->user_id = $this->authUsr['id'];
        $acts = Activity::all();

        $this->populateByCMTF($acts);
        $this->populateByGS($acts);
    }

    private function getInfoState($date): void
    {
        $areaName = $this->authUsr['area_name'];

        $this->form->date = $date;
        $this->form->act_value = 0;
        if (str_contains($this->form->time, '~')) {
            $this->infoState = $this->wtRepos->getInfoByDateAndArea($date, $areaName);
            return;
        }
        $this->infoState = $this->wtRepos->getInfoByDatetimeAndArea(
            $date, $this->form->time, $areaName
        );
    }

    private function populateByGS(Collection $acts): void
    {
        foreach ($this->locations as $location) {
            if (ActNameEnum::Outgoing->value != $location['actName']) continue;

            $this->form->act_name = $location['actName'];
            $this->form->area_loc = $location['location'];

            foreach ($acts as $act) {
                if (ActNameEnum::Outgoing->value != $act->name) continue;

                $this->form->act_process = $act->process;
                $this->form->act_unit = $act->unit;
                $this->infoState[] = $this->form->toArray();
            }
        }
    }

    private function populateByCMTF(Collection $acts): void
    {
        foreach ($acts as $act) {
            if (ActNameEnum::Outgoing->value == $act->name) continue;

            $this->form->act_name = $act->name;
            $this->form->act_process = $act->process;
            $this->form->act_unit = $act->unit; // $this->form->area_loc = str_contains($area->location, 'CMTF');

            $areaLoc = null;
            foreach ($this->locations as $location) {
                if ($act->name == $location['actName']){
                    $areaLoc = $location['location'];
                }
            }
            if ($areaLoc) $this->form->area_loc = $areaLoc;

            $this->infoState[] = $this->form->toArray();
        }
    }

    private function mapInfoState(array $infoState, $date, $time): array
    {
        return array_map(
            function ($info) use($date, $time): array {
                $info['post_id'] = $this->postId;
                $info['date'] = $date ?? $this->form->date;
                $info['time'] = $time ?? $this->form->time;

                unset($info['workTripInfoModel']);
                return $info;
            },
            array_values($infoState)
        );
    }

    /**
     * @throws ValidationException
     */
    public function onInfoStateValueSelected($idx): void
    {
        $this->form->validate(['act_value' => 'required|integer']);
        $this->infoState[$idx]['act_value'] = $this->form->act_value;
    }

    /**
     * @throws ValidationException
     */
    public function onInfoStateTimeSelected($idx): void
    {
        $this->form->validate(['time' => 'required|string']);
        $this->infoState[$idx]['time'] = $this->form->time;
    }

    public function onInfoStateActNameSelected($idx): void
    {
        try {
            if ($this->isEditMode && $info = $this->infoState[$idx]) {
                $this->delInfoQueue[] = $info;
            }
            unset($this->infoState[$idx]);
        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }
    }

    /**
     * @throws ValidationException
     */
    public function onStateInfoPressed(): void
    {
        $this->form->validate();
        $this->initInfoState();
        $this->scrollToBottom();
    }

    /**
     * @throws ValidationException
     */
    public function onDateOptionChange(): void
    {
        $this->form->validate(['date' => 'required|string']);
        // $this->focusedDate = $this->form->date;
        $this->checkInfoState(null);
        $this->scrollToBottom();
    }

    /**
     * @throws ValidationException
     */
    public function onTimeOptionChange(): void
    {
        if ($this->isEditMode) {
            $this->form->validate(['date' => 'required|string']);
        }
        $this->checkInfoState($this->dateParam);
        $this->scrollToBottom();
    }

    private function savePopulatedByDatesOrDate(): void
    {
        $dateOrDates = $this->form->date;
        if (str_contains($dateOrDates, '~')) {
            foreach ($this->datesRaw as $date) { $this->savePopulatedByTimesOrTime($date); }
        } else {
            $this->savePopulatedByTimesOrTime($dateOrDates);
        }
    }

    private function savePopulatedByTimesOrTime($date): void
    {
        if (str_contains($this->form->time, '~')) {
            foreach ($this->timesRaw as $time) { $this->savePopulated($date, $time); }
        } else {
            $this->savePopulated($date, null);
        }
    }

    private function savePopulated($date, $time): void
    {
        if ($this->isEditMode) {
            foreach ($this->infoState as $info) {
                $this->wtRepos->updateInfo($info);
            }
            return;
        }
        $infoState = $this->mapInfoState($this->infoState, $date, $time);
        foreach ($infoState as $info) {
            $this->wtRepos->addInfo($info);
        }
    }

    public function save(): void
    {
        try {
            $this->dbRepos->async();
            $this->checkPostThenGenerate();
            $this->checkInfoDeletion();
            $this->savePopulatedByDatesOrDate();
            $this->dbRepos->await();

            /*session()->flash(
                'message', 'Your change successfully saved.'
            );
            $this->isEditMode = true;
            $this->scrollToTop();*/

            $this->redirectRoute('work-trip-infos.index', navigate: true);
        } catch (\Throwable $t) {
            $this->dbRepos->cancel();
            Log::debug($t->getMessage());
            session()->flash('error', $t->getMessage());
            $this->addError('error', $t->getMessage());
        }
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.work-trip-info.create');
    }
}
