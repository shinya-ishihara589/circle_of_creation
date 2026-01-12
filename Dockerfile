# ベースの設定
FROM php:8.2-apache

# 必要な PHP 拡張
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql zip

# Apache の DocumentRoot を変更
ENV APACHE_DOCUMENT_ROOT=/var/www/html/circle_of_creation/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf

# mod_rewrite 有効化
RUN a2enmod rewrite

# Composerをインストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# デフォルトフォルダの設定
WORKDIR /var/www/html/circle_of_creation

CMD ["apache2-foreground"]
