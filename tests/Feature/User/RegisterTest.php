<?php

declare(strict_types = 1);

use App\Models\User;

use function Pest\Laravel\{assertDatabaseHas, postJson};

it('should be able to register a user', function () {
    postJson(route('api.auth.register'), [
        'first_name'            => 'John',
        'last_name'             => 'Doe',
        'email'                 => 'john.doe@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertCreated()
        ->assertJsonFragment([
            'message' => __('User created successfully. Check your email for confirmation'),
        ]);

    assertDatabaseHas('users', [
        'email'    => 'john.doe@example.com',
        'is_admin' => false,
    ]);
});

test('first name is required', function () {
    postJson(route('api.auth.register'), [
        'first_name'            => '',
        'last_name'             => 'Doe',
        'email'                 => 'john.doe@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('first_name');
});

test('last name is required', function () {
    postJson(route('api.auth.register'), [
        'first_name'            => 'John',
        'last_name'             => '',
        'email'                 => 'john.doe@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('last_name');
});

test('email is required', function () {
    postJson(route('api.auth.register'), [
        'first_name'            => 'John',
        'last_name'             => 'Doe',
        'email'                 => '',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('email');
});

test('email must be unique', function () {
    User::factory()->create(['email' => 'john.doe@example.com']);

    postJson(route('api.auth.register'), [
        'first_name'            => 'John',
        'last_name'             => 'Doe',
        'email'                 => 'john.doe@example.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('email');
});

test('password is required', function () {
    postJson(route('api.auth.register'), [
        'first_name'            => 'John',
        'last_name'             => 'Doe',
        'email'                 => 'john.doe@example.com',
        'password'              => '',
        'password_confirmation' => '',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('password');
});
