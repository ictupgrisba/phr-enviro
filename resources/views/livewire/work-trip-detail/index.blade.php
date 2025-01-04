<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Work Trip Details') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="w-full">
                <div class="sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                        <h1 class="text-base font-semibold leading-6 text-gray-900">{{ __('Work Trip Details') }}</h1>
                        <p class="mt-2 text-sm text-gray-700">A list of all the {{ __('Work Trip Details') }}.</p>
                    </div>
                    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                        <a type="button" wire:navigate href="{{ route('work-trip-details.create') }}" class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add new</a>
                    </div>
                </div>

                <div class="flow-root">
                    <div class="mt-8 overflow-x-auto">
                        <div class="inline-block min-w-full py-2 align-middle">
                            <table class="w-full divide-y divide-gray-300">
                                <thead>
                                <tr>
                                    <th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">No</th>
                                    
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Transporter</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Driver</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Police Number</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Time In</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Well Name</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Type</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Rig Name</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Load</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Volume</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Tds</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Facility</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Area Name</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Wbs Number</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Time Out</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Remarks</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Post Id</th>
									<th scope="col" class="py-3 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">User Id</th>

                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"></th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($workTripDetails as $workTripDetail)
                                    <tr class="even:bg-gray-50" wire:key="{{ $workTripDetail->id }}">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-semibold text-gray-900">{{ ++$i }}</td>
                                        
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->transporter }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->driver }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->police_number }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->time_in }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->well_name }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->type }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->rig_name }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->load }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->volume }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->tds }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->facility }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->area_name }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->wbs_number }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->time_out }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->remarks }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->post_id }}</td>
										<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $workTripDetail->user_id }}</td>

                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                            <a wire:navigate href="{{ route('work-trip-details.show', $workTripDetail->id) }}" class="text-gray-600 font-bold hover:text-gray-900 mr-2">{{ __('Show') }}</a>
                                            <a wire:navigate href="{{ route('work-trip-details.edit', $workTripDetail->id) }}" class="text-indigo-600 font-bold hover:text-indigo-900  mr-2">{{ __('Edit') }}</a>
                                            <button
                                                class="text-red-600 font-bold hover:text-red-900"
                                                type="button"
                                                wire:click="delete({{ $workTripDetail->id }})"
                                                wire:confirm="Are you sure you want to delete?"
                                            >
                                                {{ __('Delete') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="mt-4 px-4">
                                {!! $workTripDetails->withQueryString()->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>