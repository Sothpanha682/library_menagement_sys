<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     */
    public function index()
    {
        try {
            $books = Book::all();
            return response()->json([
                'success' => true,
                'message' => 'Books retrieved successfully',
                'data' => $books,
                'count' => count($books)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving books',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        // This is typically for displaying a form in a web context
        return response()->json([
            'message' => 'Create form - provide POST data to store endpoint'
        ]);
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming data
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'isbn' => 'required|string|unique:books,isbn|max:20',
                'published_year' => 'required|integer|min:1900|max:' . date('Y'),
                'category' => 'required|string|max:100',
                'quantity' => 'required|integer|min:1',
                'available_quantity' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
            ]);

            // Ensure available_quantity doesn't exceed quantity
            if ($validated['available_quantity'] > $validated['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Available quantity cannot exceed total quantity',
                    'errors' => [
                        'available_quantity' => ['Must be less than or equal to quantity']
                    ]
                ], 422);
            }

            // Create the book
            $book = Book::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Book created successfully',
                'data' => $book
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Book retrieved successfully',
                'data' => $book
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        // This is typically for displaying a form in a web context
        return response()->json([
            'message' => 'Edit form',
            'data' => $book
        ]);
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, Book $book)
    {
        try {
            // Validate the incoming data
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'author' => 'sometimes|required|string|max:255',
                'isbn' => 'sometimes|required|string|unique:books,isbn,' . $book->id . '|max:20',
                'published_year' => 'sometimes|required|integer|min:1900|max:' . date('Y'),
                'category' => 'sometimes|required|string|max:100',
                'quantity' => 'sometimes|required|integer|min:1',
                'available_quantity' => 'sometimes|required|integer|min:0',
                'description' => 'nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
            ]);

            // Check if available_quantity exceeds quantity (if both are being updated)
            $quantity = $validated['quantity'] ?? $book->quantity;
            $availableQty = $validated['available_quantity'] ?? $book->available_quantity;

            if ($availableQty > $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Available quantity cannot exceed total quantity',
                    'errors' => [
                        'available_quantity' => ['Must be less than or equal to quantity']
                    ]
                ], 422);
            }

            // Update the book
            $book->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully',
                'data' => $book
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        try {
            $bookData = $book->toArray();
            $book->delete();

            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully',
                'data' => $bookData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available books
     */
    public function getAvailable()
    {
        try {
            $books = Book::available()->get();
            return response()->json([
                'success' => true,
                'message' => 'Available books retrieved successfully',
                'data' => $books,
                'count' => count($books)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving available books',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get books by category
     */
    public function getByCategory($category)
    {
        try {
            $books = Book::byCategory($category)->get();
            return response()->json([
                'success' => true,
                'message' => 'Books retrieved by category successfully',
                'category' => $category,
                'data' => $books,
                'count' => count($books)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving books by category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update book quantity (borrow/return)
     */
    public function updateQuantity(Request $request, Book $book)
    {
        try {
            $validated = $request->validate([
                'available_quantity' => 'required|integer|min:0',
            ]);

            if ($validated['available_quantity'] > $book->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Available quantity cannot exceed total quantity',
                ], 422);
            }

            $book->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Book quantity updated successfully',
                'data' => $book
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating book quantity',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
