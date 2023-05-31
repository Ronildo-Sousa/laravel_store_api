<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\{JsonResponse, Request};

class StoreController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json();
    }
}
