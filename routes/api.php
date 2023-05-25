<?php

declare(strict_types = 1);

use App\Http\Controllers\Auth\{LoginController, RegisterController};
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::post('auth/register', RegisterController::class)->name('auth.register');
    Route::post('auth/login', LoginController::class)->name('auth.login');

    Route::middleware(['auth:sanctum'])->group(function () {
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/email/verify', function () {
        return 'You should verify your email address.';
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        dd($request->all());
        $request->fulfill();

        return redirect('/home');
    })->middleware('signed')->name('verification.verify');
});
