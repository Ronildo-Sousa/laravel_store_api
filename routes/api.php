<?php

declare(strict_types = 1);

use App\Http\Controllers\Auth\{InviteAdminController, LoginController, RegisterController, ResetPasswordController};
use App\Http\Controllers\Category\{DeleteCategoryController, ListCategoryController, ShowCategoryController, StoreCategoryController, UpdateCategoryController};
use App\Http\Controllers\Product\{ListProductController, StoreProductController};
use App\Http\Controllers\User\UpdateProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::post('auth/register', RegisterController::class)->name('auth.register');
        Route::post('auth/login', LoginController::class)->name('auth.login');
        Route::post('auth/forgot-password', [ResetPasswordController::class, 'sendResetLink'])->name('auth.forgot-password');
        Route::post('auth/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('auth.reset-password');
    });

    Route::get('/categories', ListCategoryController::class)->name('categories.index');
    Route::get('/categories/{category:slug}', ShowCategoryController::class)->name('categories.show');

    Route::get('/products', ListProductController::class)->name('products.index');

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::put('/profile-update', UpdateProfileController::class)->name('profile-update');
        Route::middleware(['isAdmin'])->group(function () {
            Route::post('auth/invite', InviteAdminController::class)->name('auth.invite');
        });

        Route::get('/dashboard', function () {
            return response()->json('this is a dashboard page');
        })->name('dashboard');

        Route::post('/categories', StoreCategoryController::class)->name('categories.store');
        Route::put('/categories/{category:slug}', UpdateCategoryController::class)->name('categories.update');
        Route::delete('/categories/{category:slug}', DeleteCategoryController::class)->name('categories.destroy');

        Route::post('/products', StoreProductController::class)->name('products.store');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return response()->json('Thanks for verifying your email address');
    })->middleware('signed')->name('verification.verify');
});
