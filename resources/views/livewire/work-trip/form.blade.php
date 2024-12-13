<div class="space-y-6">
    <div class="block md:grid md:grid-cols-4 space-y-4 md:space-y-0 gap-8">
        <div>
            <x-input-label for="date" value="{{ 'Date'.($currentDate == date('Y-m-d') ? ' (Today)' : '') }}"/>
            <x-text-input
                class="mt-1 block w-full"
                :disabled="true"
                wire:model="form.date"
                id="date" name="date"/>

            @error('form.date')
            <x-input-error class="mt-2" :messages="$message"/>
            @enderror
        </div>
        {{--<div>
            <x-input-label for="area_loc" :value="__('Location')"/>
            <x-select-option :cases="$locationOptions" wire:model="form.area_loc" id="area_loc" name="area_loc"/>
            @error('form.area_loc')
            <x-input-error class="mt-2" :messages="$message"/>
            @enderror
        </div>--}}
        <div>
            <x-input-label for="time" value="Time"/>
            <x-select-option class="mt-1 block w-full" wire:model="form.time" wire:change.prevent="onTimeOptionChange" :cases="$timeOptions" :isIdle="false" id="time" name="time"/>
            @error('form.time')
            <x-input-error class="mt-2" :messages="$message"/>
            @enderror
        </div>
        <div>
            <x-input-label for="act_value" :value="__('Quota (Actual)')"/>
            <x-text-input wire:model="form.act_value" id="act_value" name="act_value" type="number" min="0" max="999" class="mt-1 block w-full" autocomplete="act_value" placeholder="Quota"/>
            @error('form.act_value')
            <x-input-error class="mt-2" :messages="$message"/>
            @enderror
        </div>
        <div>
            <x-input-label for="btn_apply" :value="__('Apply to all')"/>
            <button id="btn_apply" name="btn_apply" type="button" wire:click="onStateInfoPressed" class="mt-1 block p-3 text-xs font-medium text-center items-center rounded-full text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 1 0-18c1.052 0 2.062.18 3 .512M7 9.577l3.923 3.923 8.5-8.5M17 14v6m-3-3h6"/></svg>
            </button>
        </div>
    </div>

    @include('livewire.work-trip.tabled')

    <div>
        <x-input-label for="remarks" :value="$remarksAt"/>
        <x-textarea wire:model="remarks" id="remarks" name="remarks" class="mt-1 block w-full" placeholder="Notes"/>
        @error('remarks')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    {{--@foreach($timeOptions as $timeOpt)
        @include('livewire.work-trip-info.tabled', ['timeOpt' => $timeOpt['value']])
    @endforeach--}}

    @error('error')
    <x-input-error class="mt-2" :messages="$message"/>
    @enderror
    <div class="flex items-center gap-4">
        <x-primary-button :disabled="count($tripState) == 0">{{$isEditMode ? 'Change' : 'Send'}}</x-primary-button>
    </div>
</div>
