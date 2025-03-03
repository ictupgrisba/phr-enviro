<?php

namespace App\Livewire\WorkTripOutDetails;

use App\Livewire\Forms\WorkTripOutDetailForm;
use App\Models\WorkTripDetailOut;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public WorkTripOutDetailForm $form;

    public function mount(WorkTripDetailOut $workTripOutDetail)
    {
        $this->form->setWorkTripOutDetailModel($workTripOutDetail);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.work-trip-out-detail.show', ['workTripOutDetail' => $this->form->workTripOutDetailModel]);
    }
}
