FROM composer:2.8 AS vendor
WORKDIR /app
COPY composer.json ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader --no-scripts

FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json ./
RUN npm install
COPY vite.config.* ./
COPY resources ./resources
COPY public ./public
RUN npm run build

FROM php:8.3-cli-alpine
WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    sqlite-dev \
    $PHPIZE_DEPS \
    && docker-php-ext-install \
        bcmath \
        ctype \
        intl \
        mbstring \
        pdo \
        pdo_sqlite \
    && apk del --no-network $PHPIZE_DEPS

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer
COPY --from=vendor /app/vendor /opt/vendor
COPY --from=assets /app/public/build /opt/public-build
COPY . .
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache database \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database \
    && chown -R www-data:www-data /var/www/html

EXPOSE 8000
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
