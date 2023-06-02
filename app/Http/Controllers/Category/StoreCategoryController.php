<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\{JsonResponse};
use Symfony\Component\HttpFoundation\Response;

class StoreCategoryController extends Controller
{
    public function __invoke(CategoryRequest $request): JsonResponse
    {
        $this->authorize('create', Category::class);

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
