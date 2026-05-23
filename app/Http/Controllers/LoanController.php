<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Eager load book relation so the frontend can show book image and details
            $loans = Loan::with('book')->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Loans retrieved successfully',
                'data' => $loans,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error retrieving loans', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|integer|exists:books,id',
            'member_id' => 'required|integer|exists:members,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrow_date',
        ]);

        try {
            $loan = DB::transaction(function () use ($validated) {
                $book = Book::lockForUpdate()->findOrFail($validated['book_id']);

                if ($book->available_quantity <= 0) {
                    throw new \Exception('No copies available for this book');
                }

                // decrement available quantity
                $book->available_quantity = max(0, $book->available_quantity - 1);
                $book->save();

                $member = Member::findOrFail($validated['member_id']);

                $loan = Loan::create([
                    'book_id' => $book->id,
                    'member_id' => $member->id,
                    'book_title' => $book->title,
                    'member_name' => $member->name,
                    'borrow_date' => $validated['borrow_date'],
                    'due_date' => $validated['due_date'],
                    'status' => 'Active',
                ]);

                return $loan;
            });

            return response()->json(['success' => true, 'message' => 'Loan created', 'data' => $loan], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error creating loan', 'error' => $e->getMessage()], 500);
        }
    }

    public function markReturned(Request $request, Loan $loan)
    {
        try {
            DB::transaction(function () use ($loan) {
                if ($loan->status !== 'Active') return;

                $loan->status = 'Returned';
                $loan->returned_at = now();
                $loan->save();

                $book = Book::lockForUpdate()->find($loan->book_id);
                if ($book) {
                    $book->available_quantity = $book->available_quantity + 1;
                    $book->save();
                }
            });

            return response()->json(['success' => true, 'message' => 'Loan marked returned', 'data' => $loan], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error returning loan', 'error' => $e->getMessage()], 500);
        }
    }
}
