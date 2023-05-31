<?php declare(strict_types = 1);

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateController extends Controller
{
    public function __invoke(CategoryRequest $request, Category $category): JsonResponse
    {
        $this->authorize('update', $category);

        $category->update($request->only('name'));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
