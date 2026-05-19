<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUsers extends Command
{
    protected $signature = 'users:create-test';
    protected $description = 'Create test users for development';

    public function handle()
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

        $this->info('Test users created successfully!');
        $this->info('');
        $this->info('Test credentials:');
        $this->line('Email: admin@libsys.com');
        $this->line('Password: password123');
        $this->line('');
        $this->line('Email: test@example.com');
        $this->line('Password: password123');
    }
}
