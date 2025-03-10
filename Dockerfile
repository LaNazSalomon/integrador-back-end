# Usa PHP 8.2 en lugar de 8.1
FROM php:8.2-cli

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    curl \
    git \
    && pecl channel-update pecl.php.net \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Configurar el directorio de trabajo
WORKDIR /app
COPY . .

# Instalar Composer manualmente si no está disponible
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Laravel y NPM
RUN composer install --no-dev --optimize-autoloader
RUN npm install --production

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
