<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserAddresses;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddresses>
 */
class UserAddressesFactory extends Factory
{
    protected $model = UserAddresses::class;
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
        return $this->afterCreating(function (UserAddresses $address, $attributes) {
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
