# Usa una imagen base de PHP con FPM
FROM php:8.1-fpm

# Instalar dependencias del sistema necesarias para la extensión MongoDB
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    && docker-php-ext-install openssl \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Configurar el directorio de trabajo
WORKDIR /app
COPY . .

# Instalar dependencias de Laravel y NPM
RUN composer install --no-dev --optimize-autoloader
RUN npm install --production

# Configurar Laravel (caché y migraciones)
RUN php artisan optimize
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan migrate --force

# Exponer el puerto de PHP-FPM
EXPOSE 9000

# Comando para iniciar PHP-FPM
CMD ["php-fpm"]
