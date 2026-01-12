# FROM php:8.2-apache

# # 必要な PHP 拡張
# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     zip \
#     libzip-dev \
#     libonig-dev \
#     libxml2-dev \
#     nodejs \
#     npm \
#     && docker-php-ext-install pdo pdo_mysql zip

# # Apache の設定
# RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/circle_of_creation/public|g' \
#     /etc/apache2/sites-available/000-default.conf \
#     && sed -i 's|<Directory /var/www/>|<Directory /var/www/html/circle_of_creation/public/>|g' \
#     /etc/apache2/apache2.conf

# RUN a2enmod rewrite

# # Composer
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# # プロジェクトコピー
# COPY . /var/www/html/circle_of_creation/

# # 権限
# RUN chmod -R 777 /var/www/html/circle_of_creation/storage \
#     /var/www/html/circle_of_creation/bootstrap/cache

# WORKDIR /var/www/html/circle_of_creation

# RUN npm install && npm run build

# CMD ["apache2-foreground"]

FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/circle_of_creation
