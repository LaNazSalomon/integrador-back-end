# Usa PHP 8.2 como imagen base
FROM php:8.2-cli

# Instalar dependencias (sin Node.js)
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    curl \
    git \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

WORKDIR /app
COPY . .

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Dependencias de PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Cache de Laravel
RUN php artisan optimize && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 9000

CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=9000"]
