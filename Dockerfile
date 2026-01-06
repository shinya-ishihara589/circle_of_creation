# Composer を使うステージ
FROM composer:2 AS composer_stage

# PHP + Apache
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Apache rewrite を有効化
RUN a2enmod rewrite

# Composer をコピー
COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

# DocumentRoot を public に変更
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Laravel プロジェクトをコピー
COPY . .

RUN mkdir -p bootstrap/cache \
    && chown -R www-data:www-data bootstrap/cache \
    && chmod -R 775 bootstrap/cache

# Composer install
RUN composer install --no-interaction --optimize-autoloader

# 権限調整
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

EXPOSE 80