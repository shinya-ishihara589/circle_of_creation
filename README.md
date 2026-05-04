docker compose exec backend composer install

# 後ほどfrontendに変更する
# node_modulesの削除
docker compose exec backend npm install
docker compose exec backend npm run build

# マイグレーション実施
docker compose exec backend php artisan migrate

# storageの権限修正
docker compose exec backend chmod -R 777 storage bootstrap/cache

# SSL認証に以下のファイルを修正※overrideを使用しているためerrorはでないと思います
docker-compose.yml
docker-compose.override.yml

cd frontend
npm install
npm run build
