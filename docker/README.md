docker-compose up --build -d

docker-compose down

docker-compose down --rmi all --volumes --remove-orphans

docker-compose exec app php artisan migrate

docker-compose exec app php artisan migrate:fresh --seed
