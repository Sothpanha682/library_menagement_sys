<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;

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

        $data = [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'published_year' => $this->faker->numberBetween(1950, date('Y')),
            'category' => $this->faker->randomElement(['Fiction', 'Non-Fiction', 'Science', 'History', 'Biography', 'Self-Help', 'Fantasy', 'Mystery']),
            'quantity' => $quantity,
            // Ensure available_quantity does not exceed quantity and defaults to quantity (most books available)
            'available_quantity' => $this->faker->numberBetween(0, $quantity),
            // Use multiple paragraphs for a fuller description when displayed
            'description' => $this->faker->paragraphs(3, true),
        ];

        // Only include `isbn` and `price` if the columns exist in the current DB schema.
        if (Schema::hasColumn('books', 'isbn')) {
            $data['isbn'] = $this->faker->isbn13();
        }

        if (Schema::hasColumn('books', 'price')) {
            $data['price'] = $this->faker->randomFloat(2, 5, 100);
        }

        return $data;
    }
}
