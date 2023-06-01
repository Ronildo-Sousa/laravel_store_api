<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'name'       => $this->name,
            'slug'       => $this->slug,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'images'     => $this->whenLoaded('images'),
        ];
    }
}