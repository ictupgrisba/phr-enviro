<x-slot name="header">
    <div class="md:inline-flex w-full justify-between space-y-2 md:space-y-0">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Actual Request
            {{--{{ request()->routeIs('work-trips.request.index') ? 'Load Request' : 'My Request' }}--}}
        </h2>
        <div>
            <label for="legendStatus">Legend</label>
            <div id="legendStatus" class="flex">
                <span
                    class="flex items-center text-sm font-medium text-gray-900 dark:text-white me-3">
                    <span class="flex w-2.5 h-2.5 bg-yellow-300 rounded-full me-1.5 flex-shrink-0"></span>
                    {{ \App\Utils\WorkTripStatusEnum::PENDING->value }}
                </span>
                <span class="flex items-center text-sm font-medium text-gray-900 dark:text-white me-3"><span
                            class="flex w-2.5 h-2.5 bg-red-500 rounded-full me-1.5 flex-shrink-0"></span>
                    {{ \App\Utils\WorkTripStatusEnum::REJECTED->value }}
                </span>
                <span class="flex items-center text-sm font-medium text-gray-900 dark:text-white me-3"><span
                            class="flex w-2.5 h-2.5 bg-green-500 rounded-full me-1.5 flex-shrink-0"></span>
                    {{ \App\Utils\WorkTripStatusEnum::APPROVED->value }}
                </span>
            </div>
        </div>
    </div>
</x-slot>

<div class="flex-col p-6 space-y-3">
    <div class="flex flex-wrap gap-3 grid-cols-3">
        @foreach($posts as $i => $post)
            <div class="relative p-4 sm:p-8 shadow sm:rounded-lg bg-white">
                @if($post->pendingCount > 0)
                    <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-yellow-300 border-2 border-white rounded-full -top-2 -start-2 dark:border-gray-900">
                        {{ $post->pendingCount }}
                    </div>
                @endif
                <div class="flex flex-col">
                    <h1 class="font-semibold leading-6 text-gray-900">
                        <a wire:navigate href="{{ route('work-trips.requests.show', $post->id) }}">{{$post->title}}</a>
                    </h1>
                    <p class="mt-2 text-sm text-gray-700">{{ $post->description }}</p>
                    <p class="mt-2 text-xs text-gray-700">{{ $post->timeAgo }}</p>
                    <dl class="divide-y mt-6 divide-gray-100">

                        {{--<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Evidence</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                <div class="flex h-40 w-40 overflow-clip rounded border">
                                    <img class="w-full h-auto object-cover"
                                         src="{{ collect($post->uploadedUrls)->map(function ($upUrl){ return $upUrl['url']; })->first() }}"
                                         alt="evidence">
                                </div>
                            </dd>
                        </div>--}}
                        {{--<div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Transporter</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ $post->transporter }}
                            </dd>
                        </div>--}}
                        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Facility Reps</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ $post->user->name ?? 'deleted account' }}
                            </dd>
                        </div>
                        @if($post->workTrips)
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900">Total request</dt>
                                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ count($post->workTrips) }}x</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium leading-6 text-gray-900"></dt>
                                <dd class="space-y-3 items-center mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    @foreach($post->workTrips as $idx => $trip)
                                        @if($idx == 2)
                                            <a class="flex" href="{{ route('work-trips.requests.show', $post->id) }}">More detail..</a>
                                            @break
                                        @endif
                                        <span class="flex items-center text-sm font-medium text-gray-900 dark:text-white me-3">
                                        @if($trip['status'] == \App\Utils\WorkTripStatusEnum::PENDING->value)
                                            <span class="flex w-2.5 h-2.5 bg-yellow-300 rounded-full me-1.5 flex-shrink-0"></span>
                                        @elseif($trip['status'] == \App\Utils\WorkTripStatusEnum::REJECTED->value)
                                            <span class="flex w-2.5 h-2.5 bg-red-600 rounded-full me-1.5 flex-shrink-0"></span>
                                        @else
                                            <span class="flex w-2.5 h-2.5 bg-green-600 rounded-full me-1.5 flex-shrink-0"></span>
                                        @endif
                                        Request {{$idx +1}}
                                        </span>
                                    @endforeach
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4 px-4">
        {!! $posts->withQueryString()->links() !!}
    </div>
</div>
