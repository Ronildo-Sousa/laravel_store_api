<?php

declare(strict_types = 1);

use App\Models\User;

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};

it('should be able to update informations of a user profile', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->put(route('api.profile-update'), [
            'first_name' => 'my updated firstname',
            'last_name'  => 'my updated lastname',
        ])->assertNoContent();

    assertDatabaseHas('users', [
        'first_name' => 'my updated firstname',
        'last_name'  => 'my updated lastname',
    ]);
})->todo();

it('should not be able to update the user email', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->put(route('api.profile-update'), [
            'email' => 'updated@email.com',
        ])->assertUnauthorized()
        ->assertSee(__('You are not authorized to perform this action.'));

    assertDatabaseMissing('users', ['email' => 'updated@email.com']);
})->todo();

it('should pass the current password when updating the user password', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->put(route('api.profile-update'), [
            'current_password'      => '',
            'password'              => 'newpassword',
            'password_confirmation' => 'newpassword',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'current_password' => [__('validation.current_password.required'), ['attribute' => 'current_password']],
        ]);
})->todo();

it('should pass the correct current  password when updating the user password', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->put(route('api.profile-update'), [
            'current_password'      => 'wrongpassword',
            'password'              => 'newpassword',
            'password_confirmation' => 'newpassword',
        ])
        ->assertUnauthorized()
        ->assertSee(__('invalid credentials'));
})->todo();
