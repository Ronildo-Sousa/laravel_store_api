<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function __invoke(StoreProductRequest $request): JsonResponse
    {
        $product = Product::query()->create($request->except('categories'));
        $product->categories()->attach($request->get('categories'));

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
