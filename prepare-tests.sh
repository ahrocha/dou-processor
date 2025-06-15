#!/bin/bash

echo "Preparando banco de dados de testes..."

DB_FILE="database/testing.sqlite"

# Cria o diretório se não existir
mkdir -p database

# Remove e recria o arquivo do banco de dados
if [ -f "$DB_FILE" ]; then
    rm "$DB_FILE"
fi
touch "$DB_FILE"

# Executa as migrations
php artisan migrate --env=testing --force

echo "Banco de dados de testes pronto."
