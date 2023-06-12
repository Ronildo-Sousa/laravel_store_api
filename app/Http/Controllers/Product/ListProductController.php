<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterDataRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListProductController extends Controller
{
    public const PER_PAGE = 10;

    public function __invoke(FilterDataRequest $request): AnonymousResourceCollection
    {
        $products = Product::with(['categories', 'images'])
            ->when(filled($request->from_categories), function (Builder $query) use ($request) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->whereIn('slug', $request->from_categories);
                });
            })
            ->paginate($request->per_page ?? self::PER_PAGE);

        return ProductResource::collection($products);
    }
}
