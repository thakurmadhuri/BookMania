<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddress>
 */
class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'address' => $this->faker->streetAddress,
            'pincode' => $this->faker->postcode,
            'default_address' => $this->faker->boolean(50), 
            'mobile' => $this->faker->phoneNumber,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (UserAddress $address, $attributes) {
            if (!isset($attributes['user_id'])) {
                if (isset($this->user)) {
                    $address->user_id = $this->user->id;
                } else {
                    $address->user_id = User::factory()->create()->id;
                }
            }
        });
    }
}
