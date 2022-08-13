<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_can_get_products()
    {
        $this->withoutExceptionHandling();

        \Artisan::call('passport:install');
        $user = User::factory()->create();
        Product::factory()->create();
        Passport::actingAs($user);
        $response = $this->get('/api/products');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'qty',
                    'price',
                    'image'
                ]
            ]
        ]);
    }

    public function test_can_get_product()
    {
        $this->withoutExceptionHandling();

        \Artisan::call('passport:install');
        $user = User::factory()->create();
        Passport::actingAs($user);
        $product = Product::factory()->create();
        $response = $this->get('/api/products/' . $product->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'qty',
                'price',
                'image'
            ]
        ]);
    }

    public function test_can_create_product()
    {
        $this->withoutExceptionHandling();
        Storage::fake('images');

        \Artisan::call('passport:install');
        $user = User::factory()->create();
        Passport::actingAs($user);
        /* test endpoint and upload image */
        $response = $this->post('/api/products', [
            'name' => 'Product 1',
            'description' => 'Description 1',
            'qty' => 1,
            'price' => 100,
            'image' => UploadedFile::fake()->image('product.jpg')
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'qty',
                'price',
                'image'
            ]
        ]);
    }

    public function test_can_update_product()
    {
        $this->withoutExceptionHandling();

        \Artisan::call('passport:install');
        $user = User::factory()->create();
        Passport::actingAs($user);
        $product = Product::factory()->create();
        $response = $this->post('/api/products/' . $product->id, [
            'name' => 'Product 1',
            'description' => 'Description 1',
            'qty' => 1,
            'price' => 1,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'qty',
                'price',
                'image'
            ]
        ]);
    }

    public function test_can_delete_product()
    {
        $this->withoutExceptionHandling();

        \Artisan::call('passport:install');
        $user = User::factory()->create();
        Passport::actingAs($user);
        $product = Product::factory()->create();
        $response = $this->delete('/api/products/' . $product->id);
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product deleted'
            ]);
    }
}
