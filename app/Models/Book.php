<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'author', 'isbn', 'published_year', 'category', 'quantity', 'available_quantity', 'description', 'price'])]
class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'published_year',
        'category',
        'quantity',
        'available_quantity',
        'description',
        'price',
    ];

    protected $casts = [
        'published_year' => 'integer',
        'quantity' => 'integer',
        'available_quantity' => 'integer',
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope to filter books by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter available books
     */
    public function scopeAvailable($query)
    {
        return $query->where('available_quantity', '>', 0);
    }

    /**
     * Check if book is in stock
     */
    public function isInStock()
    {
        return $this->available_quantity > 0;
    }
}
