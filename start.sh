#!/bin/bash

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

echo "Aguardando banco de dados."
until php artisan db:show &> /dev/null
do
  >&2 echo "Aguardando MySQL."
  sleep 2
done

echo "Executando migrations."
php artisan migrate --force || true

echo "Iniciando PHP-FPM."
exec php-fpm
