<?php declare(strict_types = 1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeleteProductController extends Controller
{
    public function __invoke(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);
        $product->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
