<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListProductController extends Controller
{
    public const PER_PAGE = 10;

    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $request->validate(['per_page' => ['integer', 'min:1', 'max:100']]);

        $products = Product::with(['categories', 'images'])->paginate($request->per_page ?? self::PER_PAGE);

        return ProductResource::collection($products);
    }
}
