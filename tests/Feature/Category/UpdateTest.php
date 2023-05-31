<?php declare(strict_types = 1);

use App\Models\{Category, User};
use Illuminate\Support\Str;

use function Pest\Laravel\{actingAs, assertDatabaseHas, putJson};

it('should be able to updadte a category', function () {
    $category = Category::factory()->create();
    actingAs(User::factory()->create(['is_admin' => true]));

    putJson(route('api.categories.update', $category->slug), [
        'name' => 'updated name',
    ])
        ->assertNoContent();

    assertDatabaseHas('categories', [
        'name' => 'updated name',
        'slug' => 'updated-name',
    ]);
});

it('should not be able to update an invalid category', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    putJson(route('api.categories.update', 'invalid-slug'))
        ->assertNotFound();
});

test('only admins can create categories', function () {
    $category = Category::factory()->create();
    actingAs(User::factory()->create(['is_admin' => false]));

    putJson(route('api.categories.update', $category->slug), [
        'name' => 'my category',
    ])
        ->assertForbidden();
});

test('category name must be unique', function () {
    $category = Category::factory()->create();
    actingAs(User::factory()->create(['is_admin' => true]));

    putJson(route('api.categories.update', $category->slug), [
        'name' => $category->name,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name' => __('validation.unique', ['attribute' => 'name']),
        ]);
});

test('category name must not be biger than 255 characters', function () {
    $category = Category::factory()->create();
    actingAs(User::factory()->create(['is_admin' => true]));

    putJson(route('api.categories.update', $category->slug), [
        'name' => Str::random(256),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255]),
        ]);
});
