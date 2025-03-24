
echo "Waiting for MySQL..."

until nc -z -v -w30 mysql_db 3306
do
  echo "Waiting for MySQL ..."
  sleep 5
done

echo "Composer installing..."
composer install --optimize-autoloader

echo "Database ready!, executing migrations..."

echo "Running migrations..."
php artisan migrate --no-interaction

echo "Running passport:install..."
php artisan passport:client --personal --no-interaction



echo "Waiting for MySQL testing..."

until nc -z -v -w30 mysql_db_testing 3306
do
  echo "Waiting for MySQL testing..."
  sleep 5
done

echo "Testing database ready!, executing migrations..."
echo "Current environment: $(php artisan env --env=testing)"

echo "Running migrations for testing..."
php artisan migrate --env=testing --no-interaction

echo "Running passport:client for testing..."
php artisan passport:client --personal --env=testing --no-interaction

echo "Running key:generate for testing..."
php artisan key:generate --env=testing --no-interaction

echo "Running passport:keys for testing..."
php artisan passport:keys --env=testing --no-interaction --force

echo "Starting Apache..."
apache2-foreground
