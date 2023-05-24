<?php

declare(strict_types = 1);

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::post('auth/register', RegisterController::class)->name('auth.register');
});
