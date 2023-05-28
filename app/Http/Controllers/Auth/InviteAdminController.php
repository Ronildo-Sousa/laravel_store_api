<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\InviteAdminRequest;
use App\Models\User;
use App\Notifications\User\InviteAdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class InviteAdminController extends Controller
{
    public function __invoke(InviteAdminRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::query()->create(
            $request->safe()->merge([
                'password' => Str::random(20),
                'is_admin' => true,
            ])->toArray()
        );
        $user->forceFill(['email_verified_at' => now()]);
        $user->save();

        $user->notify(new InviteAdminNotification());

        return response()->json(['message' => __('Invite sent successfully')], Response::HTTP_CREATED);
    }
}
