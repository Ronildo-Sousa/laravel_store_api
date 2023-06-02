<?php declare(strict_types = 1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ShowProductController extends Controller
{
    public function __invoke(Product $product): ProductResource
    {
        return new ProductResource($product->load('categories', 'images'));
    }
}
