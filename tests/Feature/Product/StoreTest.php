<?php

declare(strict_types = 1);

use App\Models\{Category, User};

use function Pest\Laravel\{actingAs, postJson};

it('should be able to create a product', function () {
    $categories = Category::factory()->count(2)->create();
    actingAs(User::factory()->create(['is_admin' => true]));

    postJson(route('api.products.store'), [
        'name'        => 'Product 1',
        'description' => 'Product 1 description',
        'price'       => 100,
        'stock'       => 10,
        'categories'  => [$categories->toArray()[0]['id'], $categories->toArray()[1]['id']],
    ])
        ->assertCreated();
});
