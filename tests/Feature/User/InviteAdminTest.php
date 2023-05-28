<?php

declare(strict_types = 1);

use App\Mail\User\InviteAdmin;
use App\Models\User;
use App\Notifications\User\InviteAdminNotification;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing, postJson};

it('should be able to invite an admin', function () {
    Notification::fake();

    actingAs(User::factory()->create(['is_admin' => true]));

    postJson(route('api.auth.invite', [
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'email'      => 'john@example.com',
    ]))
        ->assertCreated();

    assertDatabaseHas('users', ['email' => 'john@example.com']);

    Notification::assertSentTo(
        User::query()->where('email', 'john@example.com')->first(),
        InviteAdminNotification::class,
    );
});

test('only admins can invite', function () {
    Notification::fake();

    actingAs(User::factory()->create(['is_admin' => false]));

    postJson(route('api.auth.invite', [
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'email'      => 'john@example.com',
    ]))
        ->assertForbidden();

    assertDatabaseMissing('users', ['email' => 'john@example.com']);

    Notification::assertNothingSent(InviteAdmin::class);
});

test('email is required', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    postJson(route('api.auth.invite', [
        'first_name' => 'john',
        'last_name'  => 'doe',
        'email'      => '',
    ]))->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

test('email should be unique', function () {
    actingAs(User::factory()->create([
        'is_admin' => true,
        'email'    => 'john@example.com',
    ]));

    postJson(route('api.auth.invite', [
        'first_name' => 'john',
        'last_name'  => 'doe',
        'email'      => 'john@example.com',
    ]))->assertUnprocessable()
        ->assertJsonValidationErrors([
            'email' => __('validation.unique', ['attribute' => 'email']),
        ]);
});

test('first_name is required', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    postJson(route('api.auth.invite', [
        'first_name' => '',
        'last_name'  => 'Doe',
        'email'      => 'john@example.com',
    ]))->assertUnprocessable()
        ->assertJsonValidationErrors([
            'first_name' => __('validation.required', ['attribute' => 'first name']),
        ]);
});

test('last_name is required', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    postJson(route('api.auth.invite', [
        'first_name' => 'john',
        'last_name'  => '',
        'email'      => 'john@example.com',
    ]))->assertUnprocessable()
        ->assertJsonValidationErrors([
            'last_name' => __('validation.required', ['attribute' => 'last name']),
        ]);
});
