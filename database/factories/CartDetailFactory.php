<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Book;
use App\Models\CartDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartDetail>
 */
class CartDetailFactory extends Factory
{
    protected $model = CartDetail::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => Book::factory()->create()->id,
            'qty' => $this->faker->numberBetween(1, 5),
            'total_book_price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (CartDetail $cart, $attributes) {
            if (!isset($attributes['cart_id'])) {
                if (isset($this->cart)) {
                    $cart->cart_id = $this->cart->id;
                } else {
                    $cart->cart_id = Cart::factory()->create()->id;
                }
            }
        });
    }
}
