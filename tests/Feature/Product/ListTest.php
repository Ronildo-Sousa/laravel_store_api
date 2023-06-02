<?php declare(strict_types = 1);

use App\Models\Product;

use function Pest\Laravel\getJson;

use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $this->products = Product::factory()->count(10)->create();
});

it('should list all products paginated', function () {
    $response = getJson(route('api.products.index'));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(10, 'data')
        ->collect('data')
        ->each(fn ($product) => expect($this->products->where('slug', $product['slug'])->count())->toBe(1));
});

it('should change the items per page', function () {
    $response = getJson(route('api.products.index', ['per_page' => 5]));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(5, 'data');
});
