<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can fetch all products', function () {
    Product::factory()->count(5)->create();

    $response = $this->getJson('/api/v1/products');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'category_id',
                    ],
                ],
                'current_page',
                'total',
                'per_page',
            ],
            'message',
        ]);
});

it('can create a product', function () {

    $category = Category::factory()->create();

    $productData = [
        'name' => 'Test Product',
        'description' => 'Test description',
        'price' => 99.99,
        'category_id' => $category->id,
        'image_url' => 'https://example.com/image.jpg'
    ];

    $response = $this->postJson('/api/v1/products', $productData);

    $response->assertStatus(201)
        ->assertJsonFragment(['name' => 'Test Product']);
});

it('fails validation when creating a product', function () {
    $response = $this->postJson('/api/v1/products', []);

    $response->assertStatus(422);
});

it('can fetch a single product', function () {
    $product = Product::factory()->create();

    $response = $this->getJson(route('products.show', $product->id));

    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $product->id]);
});

it('returns 404 for a non-existing product', function () {
    $response = $this->getJson('/api/v1/products/999999');

    $response->assertStatus(404);
});

it('can update a product', function () {
    $product = Product::factory()->create();

    $updatedData = [
        'name' => 'Updated Product Name',
        'description' => 'Updated description',
        'price' => 199.99,
        'category_id' => $product->category_id,
        'image_url' => 'https://example.com/updated-image.jpg'
    ];

    $response = $this->putJson("/api/v1/products/{$product->id}", $updatedData);

    $response->assertStatus(200)
        ->assertJsonFragment(['name' => 'Updated Product Name']);
});

it('can delete a product', function () {
    $product = Product::factory()->create();

    $response = $this->deleteJson("/api/v1/products/{$product->id}");

    $response->assertStatus(200);
});
