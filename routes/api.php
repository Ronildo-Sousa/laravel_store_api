<?php

declare(strict_types = 1);

use App\Http\Controllers\Auth\{InviteAdminController, LoginController, RegisterAdminController, RegisterController};
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::post('auth/register', RegisterController::class)->name('auth.register');
    Route::post('auth/login', LoginController::class)->name('auth.login');

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::middleware(['isAdmin'])->group(function () {
            Route::post('auth/invite', InviteAdminController::class)->name('auth.invite');
        });

        Route::get('/dashboard', function () {
            return response()->json('this is a dashboard page');
        })->name('dashboard');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return response()->json('Thanks for verifying your email address');
    })->middleware('signed')->name('verification.verify');
});
