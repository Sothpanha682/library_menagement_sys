<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LibraryDemoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's demo data.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@libsys.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        Book::query()->delete();
        // Only seed the two specific demo books below. Removed the generic factory call
        // which previously created 20 random books.

        // Ensure specific demo books with full descriptions exist (keeps full text and image URL)
        Book::updateOrCreate(
            ['title' => 'Exposure'],
            [
                'author' => 'Ramona Emerson',
                'published_year' => 2024,
                'category' => 'Fiction',
                'quantity' => 100,
                'available_quantity' => 100,
                'description' => "Exposure is equally—if not more—electrifying than it is artful. The narrative surges forward with a boldness that grips the reader from the first chapter and never lets go. Emerson layers character and atmosphere with surgical precision, delivering suspense and emotional truth in equal measure. This is a book about the small detonations that rearrange lives, about secrets that refuse to stay buried, and about how we find ourselves again in the most unlikely places. Both propulsive and haunting, Exposure rewards the reader with sentences that linger and a plot that rewards patience.",
                'image' => 'https://t1.bookpage.com/wp-content/uploads/2024/08/22154225/exposure-733x1100.jpg',
            ]
        );

        // Seed organization logo into `settings` table
        $logoUrl = 'https://upload.wikimedia.org/wikipedia/en/thumb/a/a2/RUPP_logo.PNG/250px-RUPP_logo.PNG';
        $logoName = 'RUPP Library';

        $existingSettings = DB::table('settings')->first();
        if ($existingSettings) {
            DB::table('settings')->update([
                'logo_path' => $logoUrl,
                'logo_name' => $logoName,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('settings')->insert([
                'logo_path' => $logoUrl,
                'logo_name' => $logoName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Book::updateOrCreate(
            ['title' => 'American Scary'],
            [
                'author' => 'Jeremy Dauber',
                'published_year' => 2026,
                'category' => 'Fiction',
                'quantity' => 100,
                'available_quantity' => 99,
                'description' => "The rigorous yet still enticing American Scary invites readers into a strange and wonderfully specific landscape. Dauber's sharp eye and uncanny observations transform ordinary settings into sites of dread and fascination, while his characters feel startlingly human and fully realized. The book is at once a scholarly meditation and a wildly entertaining narrative, balancing analysis and story in ways that surprise and delight. It's a strange, rewarding read that stays with you long after the last page.",
                'image' => 'https://t1.bookpage.com/wp-content/uploads/2024/08/22153446/americanscary-728x1100.jpg',
            ]
        );

        Member::query()->delete();

        // Seed only these specific demo members
        Member::updateOrCreate(
            ['email' => 'daraksa@gmail.com'],
            [
                'name' => 'Da Raksa',
                'phone' => '097625674',
                'status' => 'Active',
            ]
        );

        Member::updateOrCreate(
            ['email' => 'somlyda@gmail.com'],
            [
                'name' => 'Som Lyda',
                'phone' => '093362323',
                'status' => 'Active',
            ]
        );

        Member::updateOrCreate(
            ['email' => 'sothpanha@gmail.com'],
            [
                'name' => 'Soth Panha',
                'phone' => '093367184',
                'status' => 'Active',
            ]
        );

        Loan::query()->delete();

        $availableBooks = Book::query()->where('available_quantity', '>', 0)->orderBy('id')->take(5)->get();
        $allBooks = Book::query()->orderBy('id')->get();
        $members = Member::query()->orderBy('id')->get();

        if ($availableBooks->isNotEmpty() && $members->isNotEmpty()) {
            foreach ($availableBooks->take(3)->values() as $index => $book) {
                $member = $members[$index % $members->count()];
                $borrowDate = now()->subDays(7 + $index * 3)->toDateString();
                $dueDate = now()->addDays(7 - $index)->toDateString();

                Loan::create([
                    'book_id' => $book->id,
                    'member_id' => $member->id,
                    'book_title' => $book->title,
                    'member_name' => $member->name,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'status' => 'Active',
                ]);

                $book->decrement('available_quantity');
            }

            foreach ($availableBooks->skip(3)->take(2)->values() as $index => $book) {
                $member = $members[($index + 3) % $members->count()];
                $borrowDate = now()->subDays(40 + $index * 5)->toDateString();
                $dueDate = now()->subDays(10 + $index * 2)->toDateString();

                Loan::create([
                    'book_id' => $book->id,
                    'member_id' => $member->id,
                    'book_title' => $book->title,
                    'member_name' => $member->name,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'status' => 'Active',
                ]);

                $book->decrement('available_quantity');
            }

            foreach ($allBooks->take(4)->values() as $index => $book) {
                $member = $members[($index + 5) % $members->count()];
                $borrowDate = now()->subDays(12 + $index * 4)->toDateString();
                $returnedAt = now()->subDays(2 + $index)->toDateString();

                Loan::create([
                    'book_id' => $book->id,
                    'member_id' => $member->id,
                    'book_title' => $book->title,
                    'member_name' => $member->name,
                    'borrow_date' => $borrowDate,
                    'due_date' => now()->subDays(1 + $index)->toDateString(),
                    'returned_at' => $returnedAt,
                    'status' => 'Returned',
                ]);
            }
        }
    }
}