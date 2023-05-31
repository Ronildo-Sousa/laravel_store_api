<?php

declare(strict_types = 1);

use App\Models\Category;

use function Pest\Laravel\getJson;

use function PHPUnit\Framework\assertCount;

use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $this->categories = Category::factory()->count(10)->create();
});

it('should be able to list paginated categories', function () {
    $response = getJson(route('api.categories.index'));

    $response->assertStatus(Response::HTTP_OK);
    assertCount($this->categories->count(), $response->collect('data'));
});

it('should be able to change the items per page', function () {
    $response = getJson(route('api.categories.index', ['per_page' => 5]));

    $response->assertStatus(Response::HTTP_OK);
    assertCount(5, $response->collect('data'));
});
