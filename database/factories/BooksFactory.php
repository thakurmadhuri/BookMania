<?php

namespace Database\Factories;

use App\Models\Books;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Books>
 */
class BooksFactory extends Factory
{
    protected $model = Books::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 100), // Generate random price with two decimal places
            'author' => $this->faker->name,
            'category_id' => function () {
                return \App\Models\Categories::factory()->create()->id;
            }
        ];
    }
}
