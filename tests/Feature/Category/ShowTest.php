<?php

declare(strict_types = 1);

use App\Models\Category;

use function Pest\Laravel\getJson;

it('should be able to get a category by slug', function () {
    $category = Category::factory()->create();

    $r = getJson(route('api.categories.show', $category->slug))
        ->assertOk()
        ->assertSee($category->name);
});

it('should not be able to get an invalid category', function () {
    getJson(route('api.categories.show', 'invalid'))
        ->assertNotFound();
});
