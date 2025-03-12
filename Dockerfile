# Usa PHP 8.2 como imagen base
FROM php:8.2-cli

# Instalar dependencias necesarias (solo PHP y MySQL)
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    curl \
    git \
    libmysqlclient-dev \
    && docker-php-ext-install pdo pdo_mysql

# Configurar el directorio de trabajo
WORKDIR /app
COPY . .

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Laravel (solo PHP)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configurar caché de Laravel
RUN php artisan optimize && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Exponer el puerto de PHP
EXPOSE 9000

# Comando para iniciar PHP (con migraciones)
CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=9000"]
