FROM php:8.3-fpm

# Instala dependências
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip soap dom

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Cria diretório e define permissões
WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]
