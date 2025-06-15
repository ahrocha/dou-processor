#!/bin/bash

echo "Aguardando banco de dados..."

until php artisan migrate:status &> /dev/null
do
  >&2 echo "Aguardando MySQL..."
  sleep 2
done

echo "Iniciando Laravel Worker..."
exec php artisan queue:work --sleep=3 --tries=3 --timeout=60
