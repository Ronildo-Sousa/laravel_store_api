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
