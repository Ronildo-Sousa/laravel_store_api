<?php declare(strict_types = 1);

use App\Models\{Category, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing, deleteJson};

it('should be able to delete a category', function () {
    $category = Category::factory()->create();
    actingAs(User::factory()->create(['is_admin' => true]));

    deleteJson(route('api.categories.destroy', $category->slug))
        ->assertNoContent();

    assertDatabaseMissing('categories', ['id' => $category->id]);
});

it('should not be able to delete an invalid category', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    deleteJson(route('api.categories.destroy', 'invalid-slug'))
        ->assertNotFound();
});

test('only admins can delete a category', function () {
    $category = Category::factory()->create();
    actingAs(User::factory()->create(['is_admin' => false]));

    deleteJson(route('api.categories.destroy', $category->slug))
        ->assertForbidden();

    assertDatabaseHas('categories', ['id' => $category->id]);
});
