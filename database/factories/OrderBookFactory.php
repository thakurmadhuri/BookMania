<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\OrderBook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderBook>
 */
class OrderBookFactory extends Factory
{
    protected $model = OrderBook::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'books_id' => function () {
                return Book::factory()->create()->id;
            },
            'qty' => $this->faker->randomNumber(1),
            'total_book_price' => $this->faker->randomFloat(2, 5, 50),
        ];
    }
}
