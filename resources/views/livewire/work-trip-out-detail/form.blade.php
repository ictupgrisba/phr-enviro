@props([
    'drilling' => \App\Utils\WorkTripDetailTypeEnum::DRILLING->value,
    'mudPit' => \App\Utils\WorkTripDetailTypeEnum::MUD_PIT->value,
    'concentratedWater' => \App\Utils\WorkTripDetailTypeEnum::CONCENTRATED_WATER->value,
    'treatedWater' => \App\Utils\WorkTripDetailTypeEnum::TREATED_WATER->value,
])
<div x-data="{ formState:{isIncoming: true, isDrilling: true, wells: @entangle('wells'), wellsCollected: [], well: '', vehicles: @entangle('vehicles'), vehCollected: [], vehicle: ''} }"
     class="space-y-6">
    <x-loading-field label="Transporter" for="transporter" required="true">
        <x-select-option
            wire:model="form.transporter" wire:change="onOperatorOptionChange" x-on:change="formState.vehCollected = []" :cases="$operators" id="transporter" name="transporter"
            type="text" class="mt-1 block w-full" autocomplete="transporter" placeholder="Transporter"/>
        @error('form.transporter')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </x-loading-field>
    <x-loading-field label="Driver Name" for="driver" required="true">
        <x-select-option wire:model="form.driver" :cases="$crews" id="driver" name="driver" type="text"
                         class="mt-1 block w-full" autocomplete="driver" placeholder="Driver"/>
        @error('form.driver')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </x-loading-field>
    <x-loading-field label="Police Number" for="police_number" required="true">
        <x-text-search-option
            id="police_number" name="police_number" type="text" class="mt-1 block w-full" placeholder="Search Police Number"
            x-model="formState.vehicle"
            x-on:focus="open = formState.vehicles.length > 0"
            x-on:input.debounce.1500ms="$wire.searchVehicleBy(formState.vehicle)">

            <template x-for="(vehicle, i) in formState.vehicles" :key="i">
                <li x-on:click="open = false; formState.vehCollected[0] = vehicle; $wire.onVehicleSelected(vehicle)"
                    x-text="vehicle.plat" class="px-4 py-2 hover:bg-indigo-100 cursor-pointer"></li>
            </template>
        </x-text-search-option>
        <div>
            <template x-for="(veh, j) in formState.vehCollected" :key="j">
                <x-text-badge-with-close-button x-text="veh.plat+', '+veh.vendor" class="my-1 mx-1"/>
            </template>
        </div>
        @error('form.police_number')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </x-loading-field>

    <div>
        <x-input-label for="incomingOrOutgoing">
            Incoming or Outgoing<span class="text-red-500">&nbsp;*</span>
        </x-input-label>
        <div id="incomingOrOutgoing" class="flex flex-wrap ms-2 space-x-6">
            <div class="flex items-center">
                <input x-model="formState.isIncoming" value="{{true}}" id="incomingOrOutgoing" name="incomingOrOutgoing"
                       type="radio"
                       class="w-4 h-4 text-yellow-400 bg-gray-100 border-gray-300 focusring-yellow-500 darkfocusring-yellow-600 darkring-offset-gray-800 focusring-2 darkbg-gray-700 darkborder-gray-600">
                <label for="red-radio" class="ms-2 text-sm font-medium text-gray-900 darktext-gray-300">Incoming</label>
            </div>
            <div class="flex items-center">
                <input x-model="formState.isIncoming" value="{{false}}" id="incomingOrOutgoing"
                       name="incomingOrOutgoing" type="radio"
                       class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focusring-green-500 darkfocusring-green-600 darkring-offset-gray-800 focusring-2 darkbg-gray-700 darkborder-gray-600">
                <label for="green-radio"
                       class="ms-2 text-sm font-medium text-gray-900 darktext-gray-300">Outgoing</label>
            </div>
        </div>
        @error('form.incomingOrOutgoing')
        <x-input-error class="mt-2" messages="Please select these option"/>
        @enderror
    </div>
    <div>
        <x-input-label for="facility">
            <span x-text="(formState.isIncoming ? 'To' : 'From')+' Facility'"></span><span class="text-red-500">&nbsp*</span>
        </x-input-label>
        <x-select-option wire:model="form.facility" :cases="$facilities" id="facility" name="facility" type="text" class="mt-1 block w-full"
                         autocomplete="facility" placeholder="Facility"/>
        @error('form.facility')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div>
        <x-input-label for="time_in">
            Time In ({{$currentDate}})<span class="text-red-500">&nbsp*</span>
        </x-input-label>
        <x-select-option class="mt-1 block w-full" wire:model="form.time_in" wire:change.prevent="onTimeOptionChange"
                         :cases="$timeOptions" :isIdle="false" id="time_in" name="time_in"/>
        @error('form.time_in')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div x-show="formState.isIncoming">
        <x-input-label for="type">
            Incoming From<span class="text-red-500">&nbsp*</span>
        </x-input-label>
        <div id="type" class="flex flex-wrap ms-2 space-x-6">
            <div class="flex items-center">
                <input wire:model="form.type" value="{{$drilling}}" id="type" name="type" type="radio"
                       class="w-4 h-4 text-yellow-400 bg-gray-100 border-gray-300 focusring-yellow-500 darkfocusring-yellow-600 darkring-offset-gray-800 focusring-2 darkbg-gray-700 darkborder-gray-600">
                <label for="red-radio" class="ms-2 text-sm font-medium text-gray-900 darktext-gray-300">{{$drilling}}</label>
            </div>
            <div class="flex items-center">
                <input wire:model="form.type" value="{{$mudPit}}" id="type" name="type" type="radio"
                       class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focusring-green-500 darkfocusring-green-600 darkring-offset-gray-800 focusring-2 darkbg-gray-700 darkborder-gray-600">
                <label for="green-radio" class="ms-2 text-sm font-medium text-gray-900 darktext-gray-300">{{$mudPit}}</label>
            </div>
        </div>
        @error('form.type')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div x-show="!formState.isIncoming">
        <x-input-label for="type">
            Outgoing For<span class="text-red-500">&nbsp*</span>
        </x-input-label>
        <div id="type" class="flex flex-wrap ms-2 space-x-6">
            <div class="flex items-center">
                <input wire:model="form.type" value="{{$treatedWater}}" id="type" name="type" type="radio"
                       class="w-4 h-4 text-yellow-400 bg-gray-100 border-gray-300 focusring-yellow-500 darkfocusring-yellow-600 darkring-offset-gray-800 focusring-2 darkbg-gray-700 darkborder-gray-600">
                <label for="red-radio" class="ms-2 text-sm font-medium text-gray-900 darktext-gray-300">{{$treatedWater}}</label>
            </div>
            <div class="flex items-center">
                <input wire:model="form.type" value="{{$concentratedWater}}" id="type" name="type" type="radio"
                       class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focusring-green-500 darkfocusring-green-600 darkring-offset-gray-800 focusring-2 darkbg-gray-700 darkborder-gray-600">
                <label for="green-radio" class="ms-2 text-sm font-medium text-gray-900 darktext-gray-300">{{$concentratedWater}}</label>
            </div>
        </div>
        @error('form.type')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    {{--<div>
        <x-input-label for="time_out" value="Time Out"/>
        <x-select-option class="mt-1 block w-full" wire:model="form.time_out" wire:change.prevent="onTimeOptionChange"
                         :cases="$timeOptions" :isIdle="false" id="time_out" name="time_out"/>
        @error('form.time_out')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>--}}

    <x-loading-field for="well" label="Well Name" required="true">
        <x-text-search-option
            id="well" name="well" class="block w-full h-11" placeholder="Search Well Name"
            x-model="formState.well"
            x-on:input.debounce.1500ms="$wire.searchWellBy(formState.well)"
            x-on:focus="open = formState.wells.length > 0">

            <template x-for="(well, i) in formState.wells" :key="i"> {{--<li x-on:click="open = false; formState.wellsCollected.push(well); $wire.onWellSelected(well)"--}}
                <li x-on:click="open = false; formState.wellsCollected[0] = well; $wire.onWellSelected(well)"
                    x-text="well.ids_wellname" class="px-4 py-2 hover:bg-indigo-100 cursor-pointer"></li>
            </template>
        </x-text-search-option>
        <div>
            <template id="wells" name="wells" x-for="(well, j) in formState.wellsCollected" :key="j">
                <x-text-badge-with-close-button x-text="well.ids_wellname+', '+well.legal_well+', '+well.well_number+', '+well.field_name" class="my-1 mx-1"/>
            </template>
        </div>
        @error('well')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </x-loading-field>
    {{--<div>
        <x-input-label for="type" :value="__('Type')"/>
        <x-text-input wire:model="form.type" id="type" name="type" type="text" class="mt-1 block w-full"
                      autocomplete="type" placeholder="Type"/>
        @error('form.type')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>--}}
    <x-loading-field for="rig_name" label="Rig Name" required="true">
        <x-text-input
            x-model="formState.wellsCollected.map(({rig_no}) => rig_no).join(', ')" disabled="true" id="rig_name" name="rig_name" type="text" class="mt-1 block w-full"
            autocomplete="rig_name" placeholder="Rig Name"/>
        @error('form.rig_name')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </x-loading-field>
    {{--<div>
        <x-input-label for="area_name">
            Area Name<span class="text-red-500">&nbsp*</span>
        </x-input-label>
        <x-text-input disabled="true" wire:model="form.area_name" id="area_name" name="area_name" type="text" class="mt-1 block w-full"
                      autocomplete="area_name" placeholder="Area Name"/>
        @error('form.area_name')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>--}}
    <x-loading-field for="wbs_number" label="WBS Number" required="true">
        <x-text-input
            x-model="formState.wellsCollected.map(({wbs_number}) => wbs_number).join(', ')" disabled="true" id="wbs_number" name="wbs_number" type="text"
            class="mt-1 block w-full" autocomplete="wbs_number" placeholder="Wbs Number"/>
        @error('form.wbs_number')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </x-loading-field>
    {{--<div>
        <x-input-label for="post_id" :value="__('Post Id')"/>
        <x-text-input wire:model="form.post_id" id="post_id" name="post_id" type="text" class="mt-1 block w-full"
                      autocomplete="post_id" placeholder="Post Id"/>
        @error('form.post_id')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div>
        <x-input-label for="user_id" :value="__('User Id')"/>
        <x-text-input wire:model="form.user_id" id="user_id" name="user_id" type="text" class="mt-1 block w-full"
                      autocomplete="user_id" placeholder="User Id"/>
        @error('form.user_id')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>--}}
    <div>
        <x-input-label for="tds" :value="__('TDS/TSS')"/>
        <x-text-input wire:model="form.tds" id="tds" name="tds" type="number" pattern="[0-9]*" inputmode="numeric" min="0" class="mt-1 block w-full" autocomplete="tds"
                      placeholder="Tds"/>
        @error('form.tds')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div>
        <x-input-label for="volume" :value="__('Volume (m3)')"/>
        <x-text-input wire:model="form.volume" id="volume" name="volume" type="number" pattern="[0-9]*" inputmode="numeric" min="0" class="mt-1 block w-full"
                      autocomplete="volume" placeholder="Volume"/>
        @error('form.volume')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div>
        <x-input-label for="load">
            Load<span class="text-red-500">&nbsp*</span>
        </x-input-label>
        <x-text-input wire:model="form.load" id="load" name="load" type="number" pattern="[0-9]*" inputmode="numeric" min="1" max="999" class="mt-1 block w-full"
                      autocomplete="load" placeholder="Total Load"/>
        @error('form.load')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div>
        <x-input-label for="remarks" :value="__('Remarks')"/>
        <x-text-input wire:model="form.remarks" id="remarks" name="remarks" type="text" class="mt-1 block w-full"
                      autocomplete="remarks" placeholder="Remarks"/>
        @error('form.remarks')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    {{--<x-general-button wire:click="check">Check</x-general-button>--}}
    @error('post_id')
    <x-input-error class="mt-2" :messages="$message"/>
    @enderror
    @error('user_id')
    <x-input-error class="mt-2" :messages="$message"/>
    @enderror
    @error('error')
    <x-input-error class="mt-2" :messages="$message"/>
    @enderror
    <div class="flex items-center gap-4">
        <x-primary-button>Submit</x-primary-button>
    </div>
</div>
