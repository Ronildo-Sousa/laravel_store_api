<?php

declare(strict_types = 1);

use App\Models\{Category, Product, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, putJson};

beforeEach(function () {
    $category      = Category::factory()->create();
    $this->product = Product::factory()->create();
    $this->product->categories()->attach($category->id);
});

it('should be able to update a product basic info', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    putJson(route('api.products.update', $this->product->slug), [
        'name'        => 'New product name',
        'description' => 'New product description',
        'price'       => 10,
        'stock'       => 5,
    ])
        ->assertNoContent();

    assertDatabaseHas('products', [
        'name'        => 'New product name',
        'slug'        => 'new-product-name',
        'description' => 'New product description',
        'price'       => (10 * 100),
        'stock'       => 5,
    ]);
});

it('should not be able to update a product with invalid data', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    putJson(route('api.products.update', $this->product->slug), [
        'name'        => '',
        'description' => 'short desc',
        'price'       => 0,
        'stock'       => 0,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name'        => __('validation.string', ['attribute' => 'name']),
            'description' => __('validation.min', ['min' => 15, 'attribute' => 'description']),
            'price'       => __('validation.min.numeric', ['min' => 1, 'attribute' => 'price']),
            'stock'       => __('validation.min.numeric', ['min' => 1, 'attribute' => 'stock']),
        ]);

    assertDatabaseHas('products', [
        'name'        => $this->product->name,
        'slug'        => $this->product->slug,
        'description' => $this->product->description,
        'price'       => ($this->product->price * 100),
        'stock'       => $this->product->stock,
    ]);
});

it('should not be able to update an invalid product', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    putJson(route('api.products.update', 'invalid'), [
        'name' => 'New product name',
    ])
        ->assertNotFound();
});

test('only admin can update a product', function () {
    actingAs(User::factory()->create(['is_admin' => false]));

    putJson(route('api.products.update', $this->product->slug), [
        'name' => 'New product name',
    ])
        ->assertForbidden();
});
