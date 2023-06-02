<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeleteCategoryController extends Controller
{
    public function __invoke(Category $category): JsonResponse
    {
        $this->authorize('delete', $category);

        $category->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
