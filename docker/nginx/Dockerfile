FROM php:8.2-fpm

# 必須パッケージ（nano含む）
RUN apt-get update && apt-get install -y \
    nano \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo_mysql zip

# Composer install
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Laravel の作業ディレクトリ
WORKDIR /var/www/html
