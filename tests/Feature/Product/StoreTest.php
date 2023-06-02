<?php

declare(strict_types = 1);

use App\Models\{Category, Product, User};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\{actingAs, assertDatabaseEmpty, assertDatabaseHas, postJson};
use function PHPUnit\Framework\assertCount;

it('should be able to create a product', function () {
    Storage::fake('product/images');

    $categories = Category::factory()->count(2)->create();
    actingAs(User::factory()->create(['is_admin' => true]));

    postJson(route('api.products.store'), [
        'name'        => 'Product 1',
        'description' => 'Product 1 description',
        'price'       => 100,
        'stock'       => 10,
        'categories'  => [$categories->toArray()[0]['id'], $categories->toArray()[1]['id']],
        'images'      => [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg'),
        ],
    ])
        ->assertCreated()
        ->assertJsonStructure(['message', 'product'])
        ->assertSee('Product 1')
        ->assertSee($categories->toArray()[0]['name'])
        ->assertSee('image1.jpg');

    assertDatabaseHas('products', ['name' => 'Product 1']);

    $product = Product::query()
        ->where('name', 'Product 1')
        ->with(['categories:id', 'images'])->first();

    assertCount(2, $product->images);

    $product->categories->each(fn ($item) => assertDatabaseHas('categorizables', [
        'category_id'      => $item->id,
        'categorizable_id' => $product->id,
    ]));
});

test('only admin can create a product', function () {
    actingAs(User::factory()->create(['is_admin' => false]));

    postJson(route('api.products.store'))
        ->assertForbidden();

    assertDatabaseEmpty('products');
});
