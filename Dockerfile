FROM composer:2.6 AS composer_stage

FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --optimize-autoloader \
    && cp .env.example .env \
    && php artisan key:generate

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

EXPOSE 9000
CMD ["php-fpm"]

