<?php

namespace Tests\Feature;

use Tests\TestCase;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->setupPermissions();
    }

    protected function setUpPermissions()
    {
        $this->artisan('db:seed', ['--class' => RolesSeeder::class]);
    }

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'sample user',
            'email' => 'sample@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123', 
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
    }
}
