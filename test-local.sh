#!/bin/bash

# Caminho do banco de dados SQLite para testes
DB_PATH="database/testing.sqlite"
ENV_FILE=".env.testing"
BACKUP_FILE=".env.testing.bak"

cp "$ENV_FILE" "$BACKUP_FILE"

# Verifica se o banco existe, senão cria
if [ ! -f "$DB_PATH" ]; then
  echo "Criando banco de dados de testes em $DB_PATH"
  touch "$DB_PATH"
fi

# Ajusta permissões
chmod 664 "$DB_PATH"

php artisan key:generate --env=testing

# Executa as migrations no ambiente de testes
docker exec -it dou-app php artisan migrate --env=testing --force

# Executa os testes
docker exec -it dou-app php artisan test --env=testing --coverage-html=coverage/

# Restaura o .env.testing original
mv "$BACKUP_FILE" "$ENV_FILE"
