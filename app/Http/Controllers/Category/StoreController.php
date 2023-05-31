<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\{JsonResponse, Request};
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->authorize('create', Category::class);
    }
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:255', 'unique:categories,name']]);

        $category = Category::query()->create($request->only('name'));

        return response()->json(
            [
                'message'  => __('Category created successfully'),
                'category' => new CategoryResource($category),
            ],
            Response::HTTP_CREATED
        );
    }
}
