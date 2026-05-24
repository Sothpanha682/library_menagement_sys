# Library Management System CRUD

## Docker Setup

This project includes a Docker-based Laravel runtime so you can run the app without installing PHP locally.

# Library Management System (CRUD)

This repository contains a Laravel-based Library Management System with CRUD for books, members, loans, notifications and users. The project can be run locally using PHP/Composer/Node or with Docker.

## Requirements

- PHP 8.1+ (or the version specified in `composer.json`)
- Composer
- Node.js + npm (or pnpm)
- A database (MySQL, PostgreSQL, or SQLite)
- (Optional) Docker & Docker Compose

## Quick Start (Local - recommended for development)

1. Clone the repository

```powershell
git clone https://github.com/Sothpanha682/library_menagement_sys.git
cd library_menagement_sys/lms
```

2. Copy environment file and set database credentials

```powershell
copy .env.example .env
# Edit .env and set DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

3. Install PHP dependencies

```powershell
composer install --no-interaction --prefer-dist
```

4. Generate application key

```powershell
php artisan key:generate
```

5. Run migrations and seeders

```powershell
php artisan migrate --seed
```

Notes:
- If you want to run only migrations without seeding: `php artisan migrate`
- To seed the demo data specifically: `php artisan db:seed --class=LibraryDemoSeeder`

6. Link storage (for user/book images)

```powershell
php artisan storage:link
```

7. Serve the application

```powershell
php artisan serve --host=127.0.0.1 --port=8000
# Then open http://127.0.0.1:8000
```

## Quick Start (Docker)

If you prefer Docker, the project includes a `docker-compose.yml` to run the app in containers.

1. Build and start containers

```powershell
docker compose up --build -d
```

2. Run migrations (inside the app container)

```powershell
docker compose exec app php artisan migrate --force --seed
```

3. Open a shell inside the container

```powershell
docker compose exec app sh
```

The app should be reachable at `http://localhost:8000` (or at the host/port configured in `docker-compose`).

## Database & Seeders

- Default seeders are in `database/seeders`. The `LibraryDemoSeeder` populates demo members, books and loans.
- To refresh the database and re-run seeders:

```powershell
php artisan migrate:fresh --seed
```

## Running Tests

Run PHPUnit tests:

```powershell
./vendor/bin/phpunit
# or
php artisan test
```

## Useful Artisan Commands

- Run migrations: `php artisan migrate`
- Seed database: `php artisan db:seed`
- Create storage symlink: `php artisan storage:link`
- Clear caches: `php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear`

## Troubleshooting

- If you see permission errors for `storage` or `bootstrap/cache`, ensure those directories are writable by your web server / user.
- If using Docker and assets or vendor files are missing, rebuild the image: `docker compose build --no-cache` then `docker compose up -d`.

## Contributing

If you'd like to contribute, please open an issue or a pull request. For local development follow the Quick Start steps above.

---

If you'd like, I can also commit this README change for you and/or open a branch. Want me to do that next?
