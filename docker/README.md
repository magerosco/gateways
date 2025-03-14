docker-compose up --build -d

docker-compose up --build --force-recreate

docker-compose down

docker-compose down -v

docker-compose down --rmi all --volumes --remove-orphans

docker-compose exec gateway_service app php artisan migrate

docker-compose exec gateway_service app php artisan migrate:fresh --seed

docker exec -it gateway_service sh -c "nc -zv redis 6379"

