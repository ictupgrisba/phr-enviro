<?php

namespace App\Livewire\PlanTrips;

use App\Models\PlanTrip;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    public function render(): View
    {
        $tripPlans = PlanTrip::paginate();

        return view('livewire.trip-plan.index', compact('tripPlans'))
            ->with('i', $this->getPage() * $tripPlans->perPage());
    }

    public function delete(PlanTrip $tripPlan): void
    {
        $tripPlan->delete();

        $this->redirectRoute(PlanTrip::ROUTE_NAME.'.index', navigate: true);
    }
}