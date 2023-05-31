<?php declare(strict_types = 1);

use App\Models\{Category, User};

use Illuminate\Support\Str;

use function Pest\Laravel\{actingAs, postJson};

it('should be able to create a category', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    postJson(route('api.categories.store', [
        'name' => 'my category',
    ]))
        ->assertCreated()
        ->assertJsonStructure(['message', 'category'])
        ->assertSee('my category')
        ->assertSee(__('Category created successfully'));
});

it('should not be able to create a category without name', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    postJson(route('api.categories.store', [
        'name' => '',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name' => __('validation.required', ['attribute' => 'name']),
        ]);
});

test('only admins can create categories', function () {
    actingAs(User::factory()->create(['is_admin' => false]));

    postJson(route('api.categories.store', [
        'name' => 'my category',
    ]))
        ->assertForbidden();
});

test('category name must be unique', function () {
    $category = Category::factory()->create();
    actingAs(User::factory()->create(['is_admin' => true]));

    postJson(route('api.categories.store', [
        'name' => $category->name,
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name' => __('validation.unique', ['attribute' => 'name']),
        ]);
});

test('category name must not be biger than 255 characters', function () {
    actingAs(User::factory()->create(['is_admin' => true]));

    postJson(route('api.categories.store', [
        'name' => Str::random(256),
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255]),
        ]);
});
