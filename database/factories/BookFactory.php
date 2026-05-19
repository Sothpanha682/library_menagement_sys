<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(5, 50);
        
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'isbn' => $this->faker->isbn13(),
            'published_year' => $this->faker->numberBetween(1950, date('Y')),
            'category' => $this->faker->randomElement(['Fiction', 'Non-Fiction', 'Science', 'History', 'Biography', 'Self-Help', 'Fantasy', 'Mystery']),
            'quantity' => $quantity,
            'available_quantity' => $this->faker->numberBetween(0, $quantity),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 5, 100),
        ];
    }
}
