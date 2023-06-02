<?php declare(strict_types = 1);

use App\Models\{Product, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing, deleteJson};

it('should be able to delete a product', function () {
    $product = Product::factory()->create();

    actingAs(User::factory()->create(['is_admin' => true]));

    deleteJson(route('api.products.destroy', $product->slug))
        ->assertNoContent();

    assertDatabaseMissing('products', ['id' => $product->id]);
});

it('should not be able to delete an invalid product', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    deleteJson(route('api.products.destroy', 'invalid-slug'))
        ->assertNotFound();
});

test('only admins can delete products', function () {
    $product = Product::factory()->create();

    actingAs(User::factory()->create(['is_admin' => false]));

    deleteJson(route('api.products.destroy', $product->slug))
        ->assertForbidden();

    assertDatabaseHas('products', ['id' => $product->id]);
});
