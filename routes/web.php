<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

require __DIR__.'/web-users.php';
require __DIR__.'/web-well.php';
require __DIR__.'/web-operator.php';
require __DIR__ . '/web-workorders.php';
require __DIR__ . '/web-worktrips.php';
require __DIR__ . '/web-logs.php';
