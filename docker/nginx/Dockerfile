FROM nginx:latest

# Nginx の設定ファイルをコンテナにコピー
COPY ./default.conf /etc/nginx/conf.d/default.conf

# Laravel のコードを置くディレクトリ
WORKDIR /var/www/html
