<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderBooks;
use App\Models\CartDetail;
use App\Models\UserAddress;
use Database\Seeders\RolesSeeder;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutProcessTest extends TestCase
{
    /**
     * A basic feature test example.
     */
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

    public function test_user_can_checkout()
    {

        $cart = Cart::factory()->create(['user_id' => $this->user->id]);

        $cartDetail=CartDetail::factory()->create(['cart_id' => $cart->id]);
        
        $response = $this->actingAs($this->user)
            ->get(route('checkout'));

        $response->assertStatus(200);

        $response->assertViewIs('checkout');

        $response->assertViewHas('user', $this->user);

        $response->assertViewHas('cart', function ($viewCart) use ($cart) {
            return $viewCart->contains($cart);
        });

        $response->assertViewHas('states', function ($viewStates) {
            return count($viewStates) === 36;
        });
    }

    public function test_user_add_address()
    {
        $response = $this->actingAs($this->user)
            ->post(route('add-address'), [
                'firstname' => 'John',
                'lastname' => 'Doe',
                'mobile' => '1234567890',
                'address' => '123 Main St',
                'pincode' => '123456',
                'city' => 'Example City',
                'state' => 'Example State',
                'country' => 'Example Country',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $this->user->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'mobile' => '1234567890',
            'address' => '123 Main St',
            'pincode' => '123456',
            'city' => 'Example City',
            'state' => 'Example State',
            'country' => 'Example Country',
            'default_address' => false,
        ]);
    }

    public function test_place_order()
    {
        $cart = Cart::factory()->create(['user_id' => $this->user->id]);
        $cartDetails = CartDetail::factory()->count(2)->create(['cart_id' => $cart->id]);

        $address = UserAddress::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->post(route('place-order'));

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
        ]);

        foreach ($cartDetails as $cartDetail) {
            $this->assertDatabaseHas('order_books', [
                'order_id' => Order::latest()->first()->id,
                'book_id' => $cartDetail->book_id,
            ]);
        }

        $this->assertNull(Session::get('cart' . $this->user->id));

        // $this->assertDatabaseMissing('carts', [
        //     'user_id' => $this->user->id,
        // ]);

        // $this->assertDatabaseMissing('cart_details', [
        //     'cart_id' => $cart->id,
        // ]);
    }

}