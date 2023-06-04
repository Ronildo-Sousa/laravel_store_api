<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'        => $this->name,
            'slug'        => $this->slug,
            'price'       => $this->price,
            'stock'       => $this->stock,
            'categories'  => CategoryResource::collection($this->whenLoaded('categories')),
            'description' => $request->routeIs('api.products.index') ? Str::limit($this->description, 70) : $this->description,
            'images'      => ImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
