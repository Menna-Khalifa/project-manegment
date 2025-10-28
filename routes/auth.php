<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;


    // Guest admin routes
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [App\Http\Controllers\Auth\AdminAuthController::class, 'create'])
            ->name('login');
        Route::post('/login', [App\Http\Controllers\Auth\AdminAuthController::class, 'store'])->name('login');
    });

    // Authenticated admin routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/logout', [App\Http\Controllers\Auth\AdminAuthController::class, 'destroy'])
            ->name('logout');
    });