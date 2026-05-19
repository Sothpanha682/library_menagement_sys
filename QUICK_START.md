# Quick Start Guide

## Start Server
```bash
cd d:\E6-WEB-PROJECTS\library-menagement-system-crud\lms
php artisan serve
```

Visit: `http://localhost:8000`

## Test Credentials

**Admin User:**
- Email: `admin@libsys.com`
- Password: `password123`

**Test User:**
- Email: `test@example.com`
- Password: `password123`

## Reset Database (if needed)
```bash
php artisan migrate:fresh --seed --force
```

This will recreate the database and add test users.

## Verify Users Exist
```bash
sqlite3 database/database.sqlite "SELECT id, name, email FROM users;"
```

## What Works

✅ Login form submits to backend  
✅ Password validation against database  
✅ Session creation on successful login  
✅ Registration form creates new users  
✅ Logout clears session  
✅ Dashboard shows when authenticated  
✅ Login form shows when not authenticated  

## API Endpoints

- `POST /api/auth/login` - Login
- `POST /api/auth/register` - Register
- `POST /api/auth/logout` - Logout
- `GET /api/auth/user` - Get current user

## File Structure

```
lms/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php (NEW - Authentication logic)
│   │       └── BookController.php
│   └── Models/
│       └── User.php
├── routes/
│   └── web.php (UPDATED - Auth routes added)
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php (UPDATED - API integration)
│       └── layouts/
│           └── app.blade.php (UPDATED - Auth JavaScript)
├── database/
│   ├── database.sqlite
│   └── seeders/
│       └── DatabaseSeeder.php (UPDATED - Test users)
└── IMPLEMENTATION_SUMMARY.md (NEW - This guide)
```

---

**Ready to test!** 🚀
