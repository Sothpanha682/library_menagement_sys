<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        Book::query()->delete();
        Book::factory(20)->create();

        Member::query()->delete();
        Member::factory(15)->create();
    }
}