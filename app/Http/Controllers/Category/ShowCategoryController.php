<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class ShowCategoryController extends Controller
{
    public function __invoke(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }
}
