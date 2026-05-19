# Login System Setup Guide

## Overview
The login system is now integrated with the backend and database. The authentication system includes login, registration, and logout functionality.

## Features Implemented

### 1. Authentication Controller (`app/Http/Controllers/AuthController.php`)
- **login()** - Authenticates user with email and password
- **register()** - Creates new user account with validation
- **getUser()** - Retrieves authenticated user info
- **logout()** - Logs out user and invalidates session

### 2. API Routes (`routes/web.php`)
```
POST   /api/auth/login      - User login
POST   /api/auth/register   - User registration
POST   /api/auth/logout     - User logout (requires auth)
GET    /api/auth/user       - Get current user (requires auth)
```

### 3. Frontend Integration
- Updated login form with real API calls
- Dynamic form validation
- Error and success messages
- Loading states during API requests
- Form data binding with Alpine.js

## Test Credentials

**Default Test User:**
- Email: `test@example.com`
- Password: (check the UserFactory for the generated password)

To create a test user with a known password, you can:

1. Run migrations and seed:
```bash
php artisan migrate:fresh --seed
```

2. Update the UserFactory to use a specific password, or manually create a user:
```bash
php artisan tinker
```

Then:
```php
use App\Models\User;
User::create([
    'name' => 'Admin User',
    'email' => 'admin@libsys.com',
    'password' => bcrypt('password123')
]);
```

## How to Test

1. **Start the Laravel development server:**
```bash
php artisan serve
```

2. **Visit the application:**
Open `http://localhost:8000` in your browser

3. **Try Login:**
- Click "Sign in"
- Enter email and password
- Click "Sign in" button
- On success, you'll be redirected to the dashboard

4. **Try Registration:**
- Click "start your 14-day free trial"
- Fill in name, email, password, and confirm password
- Click "Create Account"
- New account will be created and logged in

5. **Logout:**
- Click the logout button (red exit icon) in the sidebar

## File Changes Made

### New Files:
- `app/Http/Controllers/AuthController.php` - Authentication logic

### Modified Files:
- `routes/web.php` - Added authentication routes
- `resources/views/layouts/app.blade.php` - Added auth JavaScript functions and CSRF token
- `resources/views/auth/login.blade.php` - Updated forms to use API endpoints

## Database

The authentication uses Laravel's built-in User model with:
- Email unique constraint
- Password hashing (bcrypt)
- Session-based authentication

## Security Features

✓ CSRF token validation
✓ Password hashing with bcrypt
✓ Email validation
✓ Unique email constraint
✓ Session regeneration after login
✓ Authentication middleware for protected routes

## Next Steps

You can now:
1. Add user profile management
2. Implement "Remember Me" functionality
3. Add password reset feature
4. Create role-based access control (admin, librarian, member)
5. Add user activity logging
