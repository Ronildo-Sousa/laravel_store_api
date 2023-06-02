<?php declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\{Category, Image, Product};
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::factory()->count(10)
            ->has(Image::factory()->count(2))
            ->create();

        $products->each(function (Product $product) {
            $product->categories()->attach(Category::query()->get()->random(2)->pluck('id')->toArray());
        });
    }
}
