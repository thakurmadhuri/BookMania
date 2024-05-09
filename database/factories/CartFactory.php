<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    protected $model = Cart::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total_qty' => $this->faker->numberBetween(1, 10),
            'total_price' => $this->faker->randomFloat(2, 10, 100),
            'user_id' => User::factory()->create()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Cart $cart, $attributes) {
            if (!isset($attributes['user_id'])) {
                if (isset($this->user)) {
                    $cart->user_id = $this->user->id;
                } else {
                    $cart->user_id = User::factory()->create()->id;
                }
            }
        });
    }
}
