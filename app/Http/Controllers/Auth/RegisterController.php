<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function __invoke(RegisterUserRequest $request): JsonResponse
    {
        User::query()->create($request->validated());

        return response()
            ->json(
                [
                    'message' => __('User created successfully. Check your email for confirmation'),
                ],
                Response::HTTP_CREATED
            );
    }
}
