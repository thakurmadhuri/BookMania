<?php

namespace Database\Factories;

use App\Models\UserCart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserCart>
 */
class UserCartFactory extends Factory
{
    protected $model = UserCart::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory()->create()->id,
            'book_id' => \App\Models\Book::factory()->create()->id,
            'qty' => $this->faker->numberBetween(1, 10),
        ];
    }
}
