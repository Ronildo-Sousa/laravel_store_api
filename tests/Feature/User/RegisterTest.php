<?php

declare(strict_types = 1);

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
