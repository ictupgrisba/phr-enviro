<div class="space-y-6">

    <div>
        <x-input-label for="name" :value="__('Name')"/>
        <x-text-input wire:model="form.name" id="name" name="name" type="text" class="mt-1 block w-full" autocomplete="name" placeholder="Name"/>
        @error('form.name')
            <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    <div>
        <x-input-label for="role" :value="__('Role')"/>
        <x-select-option id="role" name="role"
            wire:model="form.role"
            :cases="\App\Utils\Enums\CrewRoleEnum::cases()"
        />
        @error('form.role')
            <x-input-error class="mt-2" :messages="$message"/>
        @enderror
        @error('form.operator_id')
        <x-input-error class="mt-2" :messages="$message"/>
        @enderror
    </div>
    {{--<div>
        <x-input-label for="operator_id" :value="__('Operator Id')"/>
        <x-text-input wire:model="form.operator_id" id="operator_id" name="operator_id" type="text" class="mt-1 block w-full" autocomplete="operator_id" placeholder="Operator Id"/>
    </div>--}}

    <div class="flex items-center gap-4">
        <x-primary-button>Submit</x-primary-button>
    </div>
</div>