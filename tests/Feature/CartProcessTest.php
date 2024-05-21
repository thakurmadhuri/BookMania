<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Cart;
use App\Models\User;
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

    public function test_remove_item_from_cart()
    {
        $book = Book::factory()->create();

        $cart = Cart::create([
            'user_id' => $this->user->id,
            'total_qty' => 1,
            'total_price' => $book->price,
        ]);

        $cartDetail = CartDetail::create([
            'cart_id' => $cart->id,
            'book_id' => $book->id,
            'qty' => 1,
            'price' => $book->price,
        ]);

        Session::put('cart.' .$this->user->id, [
            $book->id =>  1
            ]
        );

        $response = $this->postJson('/remove-item', [
            'book_id' => $book->id,
            'cart_id' => $cart->id,
        ]);

        $response->assertStatus(200);

        // $this->assertDatabaseMissing('cart_details', [
        //     'cart_id' => $cart->id,
        //     'book_id' => $book->id,
        // ]);

        // $this->assertDatabaseMissing('carts', [
        //     'id' => $cart->id,
        // ]);

        // $this->assertNull(Session::get('cart.' .$this->user->id));
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

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'total_qty' => 1,
            'total_price' => 40.00,
        ]);

        $cart = Cart::where('user_id', $this->user->id)->first();

        $this->assertDatabaseHas('cart_details', [
            'cart_id' => $cart->id,
            'book_id' => $book->id,
            'qty' => 2,
            'total_book_price' => 40.00,
        ]);

        $this->assertEquals(Session::get('cart.' . $this->user->id)[$book->id], 2);
    }

}
