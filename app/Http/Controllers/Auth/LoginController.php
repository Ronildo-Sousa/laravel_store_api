<?php declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __invoke(LoginUserRequest $request): JsonResponse
    {
        if (auth()->attempt($request->validated())) {
            auth()->login($request->user());

            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        return response()->json(['message' => __('invalid credentials')], Response::HTTP_UNAUTHORIZED);
    }
}
