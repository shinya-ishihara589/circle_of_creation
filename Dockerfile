FROM php:8.2-apache

# 必要な PHP 拡張
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    default-mysql-client \
    libmysqlclient-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql zip

# Apache の設定（DocumentRoot を circle_of_creation/public に変更）
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/circle_of_creation/public|g' \
    /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/>|<Directory /var/www/html/circle_of_creation/public/>|g' \
    /etc/apache2/apache2.conf

# mod_rewrite 有効化
RUN a2enmod rewrite

# Composer インストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# プロジェクトをコピー（正しい位置へ）
COPY . /var/www/html/circle_of_creation/

# 権限調整
RUN chmod -R 777 /var/www/html/circle_of_creation/storage \
    /var/www/html/circle_of_creation/bootstrap/cache

# npm install & build（Vite）
WORKDIR /var/www/html/circle_of_creation

RUN npm install && npm run build

# Apache 起動
CMD ["apache2-foreground"]