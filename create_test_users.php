<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create test user
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

echo "Users created successfully!\n";
echo "Test credentials:\n";
echo "Email: admin@libsys.com\n";
echo "Password: password123\n\n";
echo "Email: test@example.com\n";
echo "Password: password123\n";
