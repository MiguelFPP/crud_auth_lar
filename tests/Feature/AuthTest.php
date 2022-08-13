<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;


    public function test_can_register()
    {
        \Artisan::call('passport:install');
        $reponse = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'miguel@gmail.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);
        $reponse->assertStatus(201);
        $reponse->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ],
            'access_token',
        ]);
    }

    public function test_can_login()
    {
        \Artisan::call('passport:install');
        $user = User::factory()->create();
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ],
            'access_token',
        ]);
    }

    public function test_can_logout()
    {
        \Artisan::call('passport:install');
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->post('/api/logout');
        $response->assertStatus(200);
    }

    public function test_invalid_credentials(){
        \Artisan::call('passport:install');
        $response = $this->post('/api/login', [
            'email' => 'hola@hola.com',
            'password' => 'secret'
        ]);
        $response->assertStatus(401);
    }
}
