<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __invoke(LoginUserRequest $request): JsonResponse
    {
        if (auth()->attempt($request->validated())) {
            $user = $request->user();
            auth()->login($user);

            return response()->json([
                'user'  => new UserResource($user),
                'token' => $user->handleTokens(),
            ], Response::HTTP_OK);
        }

        return response()->json(['message' => __('invalid credentials')], Response::HTTP_UNAUTHORIZED);
    }
}
