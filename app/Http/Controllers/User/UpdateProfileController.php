<?php

declare(strict_types = 1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\{JsonResponse, Request};
use Symfony\Component\HttpFoundation\Response;

class UpdateProfileController extends Controller
{
    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        if ($request->email) {
            return response()->json([
                'message', __('You are not authorized to perform this action.'),
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$this->hasValidCredentials($request)) {
            return response()->json(['message' => __('invalid credentials')], Response::HTTP_UNAUTHORIZED);
        }

        $request->user()->update($request->validated());

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    private function hasValidCredentials(Request $request): bool
    {
        return blank($request->password)
            ? true
            : password_verify($request->current_password, $request->user()->password);
    }
}
