<?php

namespace Tests\Feature;

use App\Models\Categories;
use Tests\TestCase;
use App\Models\User;
use App\Models\Books;
use Database\Seeders\RolesSeeder;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookCrudTest extends TestCase
{
    use RefreshDatabase;
    protected $user;


    public function setUp(): void
    {
        parent::setUp();
        $this->setupPermissions();

        $this->user = User::create([
            "name" => "sample",
            "email" => "sample@gmail.com",
            "password" => bcrypt("password"),
        ]);
        $this->user->assignRole('admin');

        $this->actingAs($this->user);
    }

    protected function setUpPermissions()
    {
        $this->artisan('db:seed', ['--class' => RolesSeeder::class]);
    }

    public function test_can_create_book()
    {
        $cat = Categories::create([
            "name" => "sample",
        ]);
        
        $response = $this->post('/store-book', [
            'name' => 'sample book',
            'description' => 'description',
            'price' => '100',
            'author' => 'sample author',
            'category_id' => '1'
        ], [
            '_token' => csrf_token(),
        ]);

        $response->assertStatus(302);
    }

    // public function test_can_update_book()
    // {
    
    // }

    // public function test_can_delete_book()
    // {
  
    // }
}
