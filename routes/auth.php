<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Guest Routes
Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login')
        ->name('login');

    Volt::route('register', 'auth.register')
        ->name('register');

    Volt::route('forgot-password', 'auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password')
        ->name('password.reset');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [VerifyEmailController::class, 'sendVerificationEmail'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    Volt::route('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
        
    Volt::route('profile', 'auth.profile')
        ->name('profile');
        
    Route::post('profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');
});

// Logout Route
Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');

// Two Factor Authentication Routes
Route::middleware(['auth', '2fa'])->group(function () {
    Volt::route('two-factor-auth', 'auth.two-factor')
        ->name('2fa.setup');

});
