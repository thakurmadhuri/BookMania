<?php

namespace Database\Factories;

use App\Models\Books;
use App\Models\OrderBooks;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderBooks>
 */
class OrderBooksFactory extends Factory
{
    protected $model = OrderBooks::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'books_id' => function () {
                return Books::factory()->create()->id;
            },
            'qty' => $this->faker->randomNumber(1),
            'total_book_price' => $this->faker->randomFloat(2, 5, 50),
        ];
    }
}
