docker compose exec backend composer install

# 後ほどfrontendに変更する
# node_modulesの削除
docker compose exec backend npm install
docker compose exec backend npm run build

docker compose exec backend php artisan migrate

cd frontend
npm install
npm run build
