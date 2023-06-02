<?php declare(strict_types = 1);

use App\Models\{Category, Product};

use function Pest\Laravel\getJson;

use Symfony\Component\HttpFoundation\Response;

it('should be able to show a product', function () {
    $category = Category::factory()->create();
    $product  = Product::factory()->create();
    $product->categories()->attach($category->id);

    $response = getJson(route('api.products.show', $product->slug));

    $response->assertStatus(Response::HTTP_OK)
        ->assertSee($product->name)
        ->assertSee($product->price)
        ->assertSee($category->name);
});

it('should not be able to show a product that does not exist', function () {
    getJson(route('api.products.show', 'does-not-exist'))
        ->assertNotFound();
});
