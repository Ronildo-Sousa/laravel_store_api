<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Enums\Product\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name'        => $name,
            'slug'        => Str::slug($name),
            'description' => fake()->realText(),
            'price'       => rand(100, 900000),
            'stock'       => rand(0, 100),
            'status'      => StatusEnum::cases()[rand(0, count(StatusEnum::cases()) - 1)],
        ];
    }
}
