<?php

namespace App\Livewire\WorkTrips\Request;

use App\Livewire\Forms\PostForm;
use App\Models\Post;
use App\Policies\UserPolicy;
use App\Repositories\Contracts\IPostRepository;
use App\Repositories\Contracts\IWorkOrderRepository;
use App\Utils\WorkOrderStatusEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    private IPostRepository $postRepository;
    private IWorkOrderRepository $workOrderRepository;
    public PostForm $form;
    public ?Post $currentPost;
    public array $woIds;

    public function mount(Post $post): void
    {
        $this->currentPost = $post;
        $this->woIds = collect($post->workOrders)->map(function ($wo){ return $wo['id']; })->toArray();
        $this->form->setPostModel($post);
    }

    public function booted(IPostRepository $postRepository, IWorkOrderRepository $workOrderRepository): void
    {
        $this->postRepository = $postRepository;
        $this->workOrderRepository = $workOrderRepository;
    }

    public function onChangeStatus(string|array $woIds, string $request): void
    {
        $this->authorize(UserPolicy::IS_USER_IS_FAC_REP, $this->form->postModel);

        $onComplete = function ($id) use ($request) {
            $request = ['status' => $request];

            $updatedStateOfWorkOrder = $this->form->onChangeStateOfWorkOrder($id, $request);
            $this->currentPost->workOrders = $updatedStateOfWorkOrder;
            $this->workOrderRepository->updateWorkOrder($id, $request);
        };
        $this->form->onUpdateWorkOrders(function () use ($woIds, $onComplete) {
            if (is_string($woIds)) $onComplete($woIds);
            if (!is_array($woIds)) return;
            foreach ($woIds as $id) { $onComplete($id); }
        });
        $this->form->setResponsePostModel($this->currentPost);
    }

    public function onAllowAllRequestPressed(): void
    {
        $this->onChangeStatus(
            $this->woIds,
            WorkOrderStatusEnum::STATUS_ACCEPTED->value
        );
    }
    public function onDeniedAllRequestPressed(): void
    {
        $this->onChangeStatus(
            $this->woIds,
            WorkOrderStatusEnum::STATUS_REJECTED->value
        );
    }
    public function onDeletePressed(string $postId): void
    {
        try {
            $this->form->onRemoveEvidences(function (array $paths) {
                foreach ($paths as $path) { unlink(storage_path($path)); }
            });
        } catch (\Throwable $throwable){
            Log::debug($throwable->getMessage());

        } finally {
            $this->postRepository->removePost($postId);
            $this->redirectRoute('work-trips.index');
        }
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.work-trip.request.show', ['post' => $this->currentPost]);
    }
}
