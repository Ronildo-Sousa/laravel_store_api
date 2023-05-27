<?php

declare(strict_types = 1);

use App\Models\User;
use App\Notifications\User\ForgotPasswordNotification;
use Illuminate\Support\Facades\{DB, Notification};

use function Pest\Laravel\{assertDatabaseCount,  assertDatabaseMissing, postJson};

it('should send the reset link', function () {
    Notification::fake();

    $user = User::factory()->create();

    postJson(route('api.auth.forgot-password'), [
        'email' => $user->email,
    ])
        ->assertOk();

    assertDatabaseCount('password_reset_tokens', 1);

    Notification::assertSentTo($user, ForgotPasswordNotification::class, 1);
});

test('email is required to send reset link', function () {
    postJson(route('api.auth.forgot-password', [
        'email' => '',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email' => __('validation.required', ['attribute' => 'email']),
        ]);
});

test('email should exists on users table to send reset link', function () {
    postJson(route('api.auth.forgot-password', [
        'email' => 'not-exists@email.com',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email' => __('validation.exists', ['attribute' => 'email']),
        ]);
});

it('should be able to reset the password', function () {
    $user = User::factory()->create();

    postJson(route('api.auth.forgot-password', [
        'email' => $user->email,
    ]));

    $token = DB::table('password_reset_tokens')
        ->where('email', $user->email)->value('token');

    postJson(route('api.auth.reset-password', [
        'token'                 => $token,
        'email'                 => $user->email,
        'password'              => 'new-password',
        'password_confirmation' => 'new-password',
    ]))
        ->assertOk();

    assertDatabaseMissing('password_reset_tokens', [
        'email' => $user->email,
        'token' => $token,
    ]);
});

test('token is required to reset the password', function () {
    postJson(route('api.auth.reset-password', [
        'token'                 => '',
        'email'                 => 'john@example.com',
        'password'              => 'new-password',
        'password_confirmation' => 'new-password',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'token' => __('validation.required', ['attribute' => 'token']),
        ]);
});

test('token should exists on password_reset_tokens to reset the password', function () {
    postJson(route('api.auth.reset-password', [
        'token'                 => 'invalid token',
        'email'                 => 'john@example.com',
        'password'              => 'new-password',
        'password_confirmation' => 'new-password',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'token' => __('validation.exists', ['attribute' => 'token']),
        ]);
});

test('email should exists on password_reset_tokens to reset the password', function () {
    $user = User::factory()->create();

    postJson(route('api.auth.forgot-password', [
        'email' => $user->email,
    ]));

    $token = DB::table('password_reset_tokens')
        ->where('email', $user->email)->value('token');

    postJson(route('api.auth.reset-password', [
        'token'                 => $token,
        'email'                 => 'john@example.com',
        'password'              => 'new-password',
        'password_confirmation' => 'new-password',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email' => __('validation.exists', ['attribute' => 'email']),
        ]);
});

test('password and password_confirmation are required to reset the password', function () {
    $user = User::factory()->create();

    postJson(route('api.auth.forgot-password', [
        'email' => $user->email,
    ]));

    $token = DB::table('password_reset_tokens')
        ->where('email', $user->email)->value('token');

    postJson(route('api.auth.reset-password', [
        'token'                 => $token,
        'email'                 => $user->email,
        'password'              => '',
        'password_confirmation' => '',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'password' => __('validation.required', ['attribute' => 'password']),
        ]);
});
