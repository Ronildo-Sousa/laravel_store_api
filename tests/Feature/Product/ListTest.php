<?php

declare(strict_types = 1);

use App\Models\{Category, Product};

use function Pest\Laravel\getJson;

use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $this->categories = Category::factory()->count(5)->create();
    $this->products   = Product::factory()->count(10)->create();
    $this->products->each(fn (Product $product) => $product->categories()->attach($this->categories->random(2)));
});

it('should list all products paginated', function () {
    getJson(route('api.products.index'))
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(10, 'data')
        ->collect('data')
        ->each(fn ($product) => expect($this->products->where('slug', $product['slug'])->count())->toBe(1));
});

it('should change the items per page', function () {
    getJson(route('api.products.index', ['per_page' => 5]))
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(5, 'data');
});

it('should be able to filter products by category', function () {
    $filterCategories = $this->categories->take(2);

    $response = getJson(route('api.products.index', [
        'from_categories' => $filterCategories->pluck('slug')->toArray(),
    ]));

    $response->assertStatus(Response::HTTP_OK);

    $productsFilteredCount = $this->products->filter(
        function (Product $product) use ($filterCategories) {
            return $product->categories()->whereIn('slug', $filterCategories->pluck('slug'))->count() > 0;
        }
    )->count();

    expect($response->collect('data')->count())->toBe($productsFilteredCount);
});

test('per_page should be a positive integer less than or equal to 100', function () {
    getJson(route('api.products.index', ['per_page' => 0]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'per_page' => __('validation.min.numeric', ['min' => 1, 'attribute' => 'per page']),
        ]);

    getJson(route('api.products.index', ['per_page' => -1]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'per_page' => __('validation.min.numeric', ['min' => 1, 'attribute' => 'per page']),
        ]);

    getJson(route('api.products.index', ['per_page' => 101]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'per_page' => __('validation.max.numeric', ['max' => 100, 'attribute' => 'per page']),
        ]);
});

test('from_categories should be an array and each element should exist in categories', function () {
    getJson(route('api.products.index', ['from_categories' => 'foo']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'from_categories' => __('validation.array', ['attribute' => 'from categories']),
        ]);

    getJson(route('api.products.index', ['from_categories' => ['foo', 'bar']]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'from_categories' => __('validation.exists', ['attribute' => 'from categories']),
        ]);
});
