<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove ISBN and price completely so the schema matches the current form.
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::create('books_new', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('author');
                $table->integer('published_year');
                $table->string('category');
                $table->integer('quantity');
                $table->integer('available_quantity');
                $table->text('description')->nullable();
                $table->text('image')->nullable();
                $table->timestamps();
                $table->index('category');
                $table->index('author');
                $table->index('available_quantity');
            });

            // Copy only the columns that still exist in the simplified schema.
            DB::statement('INSERT INTO books_new (id, title, author, published_year, category, quantity, available_quantity, description, image, created_at, updated_at) SELECT id, title, author, published_year, category, quantity, available_quantity, description, image, created_at, updated_at FROM books');

            Schema::drop('books');
            Schema::rename('books_new', 'books');
        } else {
            // For other DBs, drop the columns directly.
            Schema::table('books', function (Blueprint $table) {
                $table->dropUnique(['isbn']);
                $table->dropColumn(['isbn', 'price']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::create('books_old', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('author');
                $table->integer('published_year');
                $table->string('category');
                $table->integer('quantity');
                $table->integer('available_quantity');
                $table->text('description')->nullable();
                $table->text('image')->nullable();
                $table->timestamps();
                $table->index('category');
                $table->index('author');
                $table->index('available_quantity');
            });

            // Restore the simplified schema back to the previous columns.
            DB::statement('INSERT INTO books_old (id, title, author, published_year, category, quantity, available_quantity, description, image, created_at, updated_at) SELECT id, title, author, published_year, category, quantity, available_quantity, description, image, created_at, updated_at FROM books');

            Schema::drop('books');
            Schema::rename('books_old', 'books');
        } else {
            // Add the columns back as nullable first to avoid issues when data is missing.
            Schema::table('books', function (Blueprint $table) {
                if (!Schema::hasColumn('books', 'isbn')) {
                    $table->string('isbn')->nullable();
                }
                if (!Schema::hasColumn('books', 'price')) {
                    $table->decimal('price', 10, 2)->nullable();
                }
            });

            // Attempt to create a unique index for ISBN only if there are no duplicate non-null ISBNs.
            try {
                $duplicatesExist = DB::table('books')
                    ->select('isbn', DB::raw('COUNT(*) as cnt'))
                    ->whereNotNull('isbn')
                    ->groupBy('isbn')
                    ->having('cnt', '>', 1)
                    ->limit(1)
                    ->exists();

                if (!$duplicatesExist) {
                    // Create unique index safely
                    Schema::table('books', function (Blueprint $table) {
                        // Only add the index if it doesn't already exist
                        $sm = Schema::getConnection()->getDoctrineSchemaManager();
                        $doctrineTable = $sm->listTableDetails($table->getTable());
                        if (!$doctrineTable->hasIndex('books_isbn_unique')) {
                            $table->unique('isbn');
                        }
                    });
                }
            } catch (\Exception $e) {
                // If anything goes wrong (driver differences, doctrine not available, etc.),
                // skip creating the unique index to avoid failing the rollback.
            }
        }
    }
};
