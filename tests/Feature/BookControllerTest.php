<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting all books
     */
    public function test_get_all_books(): void
    {
        Book::factory(5)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Books retrieved successfully',
            ])
            ->assertJsonCount(5, 'data');
    }

    /**
     * Test creating a new book
     */
    public function test_create_book(): void
    {
        $bookData = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'isbn' => '978-3-16-148410-0',
            'published_year' => 2023,
            'category' => 'Fiction',
            'quantity' => 10,
            'available_quantity' => 8,
            'description' => 'A test book description',
            'price' => 29.99,
        ];

        $response = $this->postJson('/api/books', $bookData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Book created successfully',
            ]);

        $this->assertDatabaseHas('books', ['isbn' => '978-3-16-148410-0']);
    }

    /**
     * Test getting a single book
     */
    public function test_get_single_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $book->id,
                    'title' => $book->title,
                ],
            ]);
    }

    /**
     * Test updating a book
     */
    public function test_update_book(): void
    {
        $book = Book::factory()->create();

        $updateData = [
            'title' => 'Updated Title',
            'price' => 49.99,
        ];

        $response = $this->putJson("/api/books/{$book->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Book updated successfully',
            ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title',
        ]);
    }

    /**
     * Test deleting a book
     */
    public function test_delete_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Book deleted successfully',
            ]);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /**
     * Test getting available books
     */
    public function test_get_available_books(): void
    {
        Book::factory(3)->create(['available_quantity' => 5]);
        Book::factory(2)->create(['available_quantity' => 0]);

        $response = $this->getJson('/api/books/available/all');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test getting books by category
     */
    public function test_get_books_by_category(): void
    {
        Book::factory(3)->create(['category' => 'Fiction']);
        Book::factory(2)->create(['category' => 'Science']);

        $response = $this->getJson('/api/books/category/Fiction');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test validation on book creation
     */
    public function test_create_book_validation(): void
    {
        $response = $this->postJson('/api/books', [
            'title' => 'Test',
            // Missing required fields
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    /**
     * Test duplicate ISBN validation
     */
    public function test_duplicate_isbn_validation(): void
    {
        $book = Book::factory()->create();

        $response = $this->postJson('/api/books', [
            'title' => 'Another Book',
            'author' => 'Author Name',
            'isbn' => $book->isbn, // Same ISBN
            'published_year' => 2023,
            'category' => 'Fiction',
            'quantity' => 10,
            'available_quantity' => 8,
            'price' => 29.99,
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test available quantity cannot exceed total quantity
     */
    public function test_available_quantity_validation(): void
    {
        $response = $this->postJson('/api/books', [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'isbn' => '978-3-16-148410-0',
            'published_year' => 2023,
            'category' => 'Fiction',
            'quantity' => 10,
            'available_quantity' => 15, // More than total quantity
            'price' => 29.99,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Available quantity cannot exceed total quantity',
            ]);
    }
}
