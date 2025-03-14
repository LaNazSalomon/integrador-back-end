# Usar imagen de PHP 8.2 con FPM (para integrar con Nginx)
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    curl \
    git \
    libmariadb-dev \
    libcurl4-openssl-dev \
    libzip-dev \
    nginx \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    opcache \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Configurar Nginx
COPY .docker/nginx.conf /etc/nginx/sites-available/default

# Configurar el directorio de trabajo
WORKDIR /var/www/html
COPY . .

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Laravel (sin paquetes de desarrollo)
RUN composer install --no-dev --optimize-autoloader

# Permisos para storage y cache
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Dentro de tu Dockerfile, agrega:
RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql

# Optimizar Laravel
RUN php artisan optimize && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Exponer puertos para Nginx y PHP-FPM
EXPOSE 80

# Comando para iniciar servicios (Nginx + PHP-FPM)
CMD sh -c "php artisan migrate --force && service nginx start && php-fpm"
