<?php

use App\Livewire\Actions\Logout;
use App\Repositories\Contracts\IUserRepository;
use Livewire\Volt\Component;

new class extends Component {
    public array $authUsr;
    /**
     * Log the current user out of the application.
     */
    public function mount(IUserRepository $usrRepos): void
    {
        $this->authUsr = $usrRepos->authenticatedUser()->toArray();
    }

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>
@props([
    'areaName' => ucfirst(strtolower($authUsr['area_name'] ?? 'NA')),
    'username' => $authUsr['username'] ?? 'NA'
])
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <img class="block h-9 w-auto fill-current text-gray-800" src="{{ asset('oil-rig-icon.svg') }}"
                             alt="{{config('app.name')}} logo"/>
                        {{--<x-application-logo class="block h-9 w-auto fill-current text-gray-800" />--}}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @can(\App\Policies\UserPolicy::IS_NOT_GUEST_ROLE)
                        <x-nav-link :href="route('well-masters.index')"
                                    :active="request()->routeIs('well-masters.*')" wire:navigate>
                            {{ __('Well Master') }}
                        </x-nav-link>
                    @endcan
                    @can(\App\Policies\UserPolicy::IS_USER_IS_FAC_REP)
                        <x-nav-link :href="route('work-trip-infos.index')"
                                    :active="request()->routeIs('work-trip-infos.*')" wire:navigate>
                            VT Planing
                        </x-nav-link>
                        <x-nav-link :href="route('work-trip-details.index')"
                                    :active="request()->routeIs('work-trip-details.index')" wire:navigate>
                            VT Log Sheet Report
                        </x-nav-link>
                        <x-nav-link :href="route('work-trips.requests.index')"
                                    :active="request()->routeIs('work-trips.requests.*')" wire:navigate>
                            VT Verification
                        </x-nav-link>
                        <x-nav-link :href="route('work-trip-details.report')" :active="request()->routeIs('work-trip-details.report')"
                                    wire:navigate>
                            Facility Monthly Report
                        </x-nav-link>
                        <x-nav-link :href="route('work-trips.report')" :active="request()->routeIs('work-trips.report')"
                                    wire:navigate>
                            Facility Daily Report
                        </x-nav-link>
                    @endcan
                    @can(\App\Policies\UserPolicy::IS_USER_IS_PM_COW)
                        <x-nav-link :href="route('work-trips.index')"
                                    :active="request()->routeIs('work-trips.requests.*')" wire:navigate>
                            VT Actual
                        </x-nav-link>
                    @endcan
                    @can(\App\Policies\UserPolicy::IS_USER_IS_VT_CREW)
                        <x-nav-link :href="route('work-trip-in-details.index')"
                                    :active="request()->routeIs('work-trip-in-details.*')" wire:navigate>
                            Un/Loading In
                        </x-nav-link>
                        <x-nav-link :href="route('work-trip-out-details.index')"
                                    :active="request()->routeIs('work-trip-out-details.*')" wire:navigate>
                            Un/Loading Out
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Notification Button & Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <sup>({{ $areaName }})</sup>&nbsp;
                            <div x-data="{{ json_encode(['name' => $username ]) }}" x-text="name"
                                 x-on:profile-updated.window="name = $event.detail.name">
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
                @can(\App\Policies\UserPolicy::IS_USER_IS_FAC_REP)
                    <x-nav-link class="border-none" :href="route('logs.index')" :active="request()->routeIs('logs.*')"
                                wire:navigate>
                        <div class="ms-1">
                            <img class="fill-current h-4 w-4" src="{{ asset('/csv/notifications_unread.svg') }}"
                                 alt="Notification">
                        </div>
                    </x-nav-link>
                @endcan
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @can(\App\Policies\UserPolicy::IS_NOT_GUEST_ROLE)
                <x-responsive-nav-link :href="route('well-masters.index')"
                                       :active="request()->routeIs('well-masters.*')" wire:navigate>
                    {{ __('Well Master') }}
                </x-responsive-nav-link>
            @endcan

            @can(\App\Policies\UserPolicy::IS_USER_IS_FAC_REP)
                <x-responsive-nav-link :href="route('work-trip-infos.index')"
                                       :active="request()->routeIs('work-trip-infos.*')" wire:navigate>
                    VT Planing
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('work-trip-details.index')"
                                       :active="request()->routeIs('work-trip-details.index')" wire:navigate>
                    VT Log Sheet Report
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('work-trips.requests.index')"
                                       :active="request()->routeIs('work-trips.requests.*')" wire:navigate>
                    VT Verification
                <x-responsive-nav-link :href="route('work-trip-details.report')"
                                       :active="request()->routeIs('work-trip-details.report')" wire:navigate>
                    Facility Monthly Report
                </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('work-trips.report')"
                                       :active="request()->routeIs('work-trips.report')" wire:navigate>
                    Facility Daily Report
                </x-responsive-nav-link>
                </x-responsive-nav-link>
            @endcan
            @can(\App\Policies\UserPolicy::IS_USER_IS_PM_COW)
                <x-responsive-nav-link :href="route('work-trips.index')" :active="request()->routeIs('work-trips.*')"
                                       wire:navigate>
                    VT Actual
                </x-responsive-nav-link>
            @endcan
            @can(\App\Policies\UserPolicy::IS_USER_IS_VT_CREW)
                <x-responsive-nav-link :href="route('work-trip-in-details.index')"
                                       :active="request()->routeIs('work-trip-in-details.*')" wire:navigate>
                    Un/Loading In
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('work-trip-out-details.index')"
                                       :active="request()->routeIs('work-trip-out-details.*')" wire:navigate>
                    Un/Loading Out
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800"
                     x-data="{{ json_encode(['name' => $username]) }}"
                     x-text="name"
                     x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ $username }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{--<div class="relative w-56 h-56 bg-gray-100 border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                        <span class="bg-blue-200 text-xs font-medium text-blue-800 text-center p-0.5 leading-none rounded-full px-2 dark:bg-blue-900 dark:text-blue-200 absolute -translate-y-1/2 -translate-x-1/2 right-auto top-0 left-0">top-left</span>
                    </div>--}}
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                @can(\App\Policies\UserPolicy::IS_USER_IS_FAC_REP)
                    <x-responsive-nav-link :href="route('logs.index')" :active="request()->routeIs('logs.*')"
                                           wire:navigate>
                        {{ __('Notification') }}
                    </x-responsive-nav-link>
                @endcan
                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
