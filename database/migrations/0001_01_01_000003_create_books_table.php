<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->unique();
            $table->integer('published_year');
            $table->string('category');
            $table->integer('quantity'); // Total quantity in library
            $table->integer('available_quantity'); // How many are available to borrow
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();

            // Indexes for better query performance
            $table->index('category');
            $table->index('author');
            $table->index('available_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
