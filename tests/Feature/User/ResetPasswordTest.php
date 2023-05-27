<?php declare(strict_types = 1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\{assertDatabaseCount, postJson};

it('should send the reset link', function () {
    Notification::fake();

    $user = User::factory()->create();

    postJson(route('password.email'), [
        'email' => $user->email,
    ])
        ->assertOk();

    assertDatabaseCount('password_reset_tokens', 1);

    Notification::assertSentTo($user, ResetPassword::class);
});
