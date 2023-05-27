<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\User\ForgotPasswordNotification;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{DB, Notification, Password};

class ResetPasswordController extends Controller
{
    public function sendResetLink(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email', 'exists:users,email']]);

        $token = bcrypt($request->email);
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => $token,
            'created_at' => now(),
        ]);

        /** @var User $user */
        $user = User::query()->where('email', $request->email)->first();

        $user->notify(new ForgotPasswordNotification($token));

        return response()->json(['message' => __('We have emailed your password reset link.')]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token'    => ['required', 'exists:password_reset_tokens,token'],
            'email'    => ['required', 'email', 'exists:password_reset_tokens,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var User $user */
        $user = User::query()->where('email', $request->email)->first();

        $user->update(['password' => $request->password]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => __('Your password has been reset.')]);
    }
}
