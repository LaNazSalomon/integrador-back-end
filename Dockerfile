# Usa PHP 8.2 como imagen base
FROM php:8.2-cli

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    curl \
    git \
    libmariadb-dev \
    && pecl channel-update pecl.php.net \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo pdo_mysql

# Configurar el directorio de trabajo
WORKDIR /app
COPY . .

# Instalar Composer manualmente si no está disponible
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Configurar Laravel (caché y migraciones)
RUN php artisan optimize
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan migrate --force

# Exponer el puerto de PHP
EXPOSE 9000

# Comando para iniciar PHP
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
