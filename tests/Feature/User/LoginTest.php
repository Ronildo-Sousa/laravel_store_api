<?php

declare(strict_types = 1);

use App\Models\User;

use function Pest\Laravel\{assertAuthenticatedAs, postJson};

it('should be able to login', function () {
    $user = User::factory()->create();

    postJson(route('api.auth.login', [
        'email'    => $user->email,
        'password' => 'password',
    ]))
        ->assertOk()
        ->assertJsonStructure(['user', 'token'])
        ->assertSee($user->email, $user->tokens);

    assertAuthenticatedAs($user);
});

it('should not be able to login with wrong credentials', function () {
    $user = User::factory()->create();

    postJson(route('api.auth.login', [
        'email'    => $user->email,
        'password' => 'wrong-password',
    ]))
        ->assertUnauthorized()
        ->assertSee(__('invalid credentials'));

    expect(auth()->check())->toBeFalse();
});

test('email is required', function () {
    postJson(route('api.auth.login', [
        'email'    => '',
        'password' => 'password',
    ]))->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

test('email is valid', function () {
    postJson(route('api.auth.login', [
        'email'    => 'invalid-email',
        'password' => 'password',
    ]))->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

test('password is required', function () {
    $user = User::factory()->create();

    postJson(route('api.auth.login', [
        'email'    => $user->email,
        'password' => '',
    ]))->assertUnprocessable()
        ->assertJsonValidationErrorFor('password');
});
