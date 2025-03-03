@props(['cases' => null, 'isIdle' => true, 'areValueAndNameTheSame' => false])
<div>
    @if(!is_null($cases))
        <select
            {!! $attributes->merge(['class' => 'block w-full px-4 pe-8 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 hover:opacity-50']) !!}
            name="roles" id="roles">
            @if($isIdle) <option value="">All</option> @endif
            @foreach($cases as $case)
                <option value="{{ $areValueAndNameTheSame ? ($case->name ?? $case['name'] ?? 'NA') : ($case->value ?? $case['value'] ?? 'NA') }}">
                    {{ $case->name ?? $case['name'] ?? 'NA' }}
                </option>
            @endforeach
        </select>
    @else
        <x-input-error class="mt-2" messages="Undefined input parameter"/>
    @endif
</div>
