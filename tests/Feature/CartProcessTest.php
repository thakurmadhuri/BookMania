<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Cart;
use App\Models\User;
use App\Models\UserCart;
use App\Models\CartDetail;
use Database\Seeders\RolesSeeder;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartProcessTest extends TestCase
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
            "password" => bcrypt("Password@123"),
        ]);
        $this->user->assignRole('user');

        $this->actingAs($this->user);
    }

    protected function setUpPermissions()
    {
        $this->artisan('db:seed', ['--class' => RolesSeeder::class]);
    }

    public function testMyCart()
    {
        $userCarts = UserCart::factory()->count(5)->create(); 

        $response = $this->json('GET', '/cart');

        $response->assertStatus(200);
    }

    public function testCartCount()
    {
        $userCarts = UserCart::factory()->count(5)->create(['user_id' => $this->user->id]); 

        $response = $this->json('GET', route('cart.count'));

        $response->assertStatus(200)
                 ->assertJson(['count' => 5]);

        $userCarts->each->delete();

        $response = $this->json('GET', route('cart.count'));

        $response->assertStatus(200)
                 ->assertJson(['count' => 0]);
    }

    public function test_remove_item_from_cart()
    {
        $book = Book::factory()->create();

        $cart = UserCart::factory()->create(['user_id' => $this->user->id]);

        $response = $this->postJson('/remove-item', [
            'book_id' => $book->id,
            'cart_id' => $cart->id,
        ]);

        $response->assertStatus(200);

    }

    public function test_can_add_item_to_cart()
    {

        $book = Book::factory()->create([
            'price' => 20.00,
        ]);

        $response = $this->postJson('store-cart', [
            'book_id' => $book->id,
            'quantity' => 2,
            'total' => 40.00,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('user_carts', [
            'user_id' => $this->user->id,
            'qty' => 2,
            'book_id' => $book->id,
        ]);

    }

}
