<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderBook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => $this->faker->unique()->randomNumber(),
            'total_qty' => $this->faker->randomNumber(2),
            'total_price' => $this->faker->randomFloat(2, 10, 100),
            'payment_method' => $this->faker->randomElement(['CASH', 'CARD']),
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'address' => $this->faker->streetAddress,
            'pincode' => $this->faker->postcode,
            'mobile' => $this->faker->phoneNumber,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $order->books()->saveMany(OrderBook::factory()->count(2)->create(['order_id' => $order->id]));
        });
    }
}
