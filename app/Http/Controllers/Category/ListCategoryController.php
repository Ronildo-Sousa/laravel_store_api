<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListCategoryController extends Controller
{
    public const PER_PAGE = 10;

    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'per_page' => ['integer', 'min:1', 'max:100'],
        ]);

        return CategoryResource::collection(Category::query()->paginate(
            $request->get('per_page') ?? self::PER_PAGE,
        ));
    }
}
