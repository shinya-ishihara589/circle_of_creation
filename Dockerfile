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

# Apache の DocumentRoot を Laravel の public に変更
ENV APACHE_DOCUMENT_ROOT=/var/www/html/circle_of_creation/public

# DocumentRoot と Directory のみ正しく置換
RUN sed -ri -e "s#DocumentRoot /var/www/html#DocumentRoot ${APACHE_DOCUMENT_ROOT}#g" \
    /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e "s#<Directory /var/www/>#<Directory ${APACHE_DOCUMENT_ROOT}>#g" \
    /etc/apache2/apache2.conf

# AllowOverride All を有効化（Laravel のルーティングに必須）
RUN sed -ri -e "s#AllowOverride None#AllowOverride All#g" /etc/apache2/apache2.conf

# mod_rewrite 有効化
RUN a2enmod rewrite

# Composer をインストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Laravel プロジェクトをコンテナにコピー（← 必須）
COPY . /var/www/html/circle_of_creation

# Laravel の権限設定（← COPY の直後が正しい位置）
RUN chown -R www-data:www-data /var/www/html/circle_of_creation/storage \
    && chown -R www-data:www-data /var/www/html/circle_of_creation/bootstrap/cache \
    && chmod -R 775 /var/www/html/circle_of_creation/storage \
    && chmod -R 775 /var/www/html/circle_of_creation/bootstrap/cache

# 作業ディレクトリ
WORKDIR /var/www/html/circle_of_creation

CMD ["apache2-foreground"]