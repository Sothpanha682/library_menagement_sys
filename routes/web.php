<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;

Route::get('/', function () {
    return view('layouts.app');
});

Route::post('/', function () {
    return redirect('/');
});

Route::get('/dashboard', function () {
    return view('layouts.app');
});

// Authentication Routes
Route::post('/api/auth/login', [AuthController::class, 'login']);
Route::post('/api/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/api/auth/user', [AuthController::class, 'getUser'])->middleware('auth:sanctum');

// Books Inventory CRUD Routes
Route::prefix('api/books')->group(function () {
    // Standard CRUD routes
    Route::get('', [BookController::class, 'index']);                    // GET all books
    Route::post('', [BookController::class, 'store']);                   // POST create book
    Route::get('{book}', [BookController::class, 'show']);               // GET single book
    Route::put('{book}', [BookController::class, 'update']);             // PUT update book
    Route::patch('{book}', [BookController::class, 'update']);           // PATCH update book
    Route::delete('{book}', [BookController::class, 'destroy']);         // DELETE book

    // Additional routes
    Route::get('available/all', [BookController::class, 'getAvailable']); // GET available books
    Route::get('category/{category}', [BookController::class, 'getByCategory']); // GET books by category
    Route::patch('{book}/quantity', [BookController::class, 'updateQuantity']); // Update quantity
});

// Members Management CRUD Routes
Route::prefix('api/members')->group(function () {
    Route::get('', [MemberController::class, 'index']);                  // GET all members
    Route::post('', [MemberController::class, 'store']);                 // POST create member
    Route::get('{member}', [MemberController::class, 'show']);           // GET single member
    Route::put('{member}', [MemberController::class, 'update']);         // PUT update member
    Route::patch('{member}', [MemberController::class, 'update']);       // PATCH update member
    Route::delete('{member}', [MemberController::class, 'destroy']);     // DELETE member
});
