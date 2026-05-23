#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ -f .env ]; then
    app_key_value=$(grep '^APP_KEY=' .env | head -n 1 | cut -d= -f2-)
    if [ -z "$app_key_value" ]; then
        export APP_KEY="$(php artisan key:generate --show --no-ansi)"
    else
        export APP_KEY="$app_key_value"
    fi
fi

mkdir -p database
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi

if [ ! -f vendor/autoload.php ] && [ -d /opt/vendor ]; then
    mkdir -p vendor
    cp -R /opt/vendor/. vendor/
fi

if [ ! -d public/build ] && [ -d /opt/public-build ]; then
    mkdir -p public/build
    cp -R /opt/public-build/. public/build/
fi

php artisan migrate --force --no-interaction

exec "$@"
