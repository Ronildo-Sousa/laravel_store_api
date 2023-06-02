<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\{JsonResponse};
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class StoreProductController extends Controller
{
    public function __construct()
    {
        $this->authorize('create', Product::class);
    }

    public function __invoke(StoreProductRequest $request): JsonResponse
    {
        $product = Product::query()->create($request->except('categories'));
        $product->categories()->attach($request->get('categories'));

        collect($request->file('images'))->each(function ($image) use ($product) {
            $filename = time() . '_' . Str::remove(' ', $image->getClientOriginalName());
            $filepath = $image->storeAs("product/images/{$product->id}", $filename, 'public');

            $product->images()->create([
                'name' => $filename,
                'path' => $filepath,
            ]);
        });

        $product = Product::query()->where('id', $product->id)
            ->with(['categories:name,slug', 'images'])->first();

        return response()->json([
            'message' => __('Product created successfully'),
            'product' => new ProductResource($product),
        ], Response::HTTP_CREATED);
    }
}
