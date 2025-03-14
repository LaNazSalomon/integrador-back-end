# Usar imagen oficial de PHP 8.2 con FPM
FROM php:8.2-fpm

# Instalar dependencias, nginx y supervisor
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
    supervisor \
    && docker-php-ext-install pdo_mysql zip opcache \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Configurar Nginx (se asume que tu archivo está en .docker/nginx.conf)
COPY .docker/nginx.conf /etc/nginx/sites-available/default

# Copiar archivo de configuración de Supervisor
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Configurar el directorio de trabajo y copiar la aplicación
WORKDIR /var/www/html
COPY . .

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Laravel (sin paquetes de desarrollo)
RUN composer install --no-dev --optimize-autoloader

# Ajustar permisos para storage y bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Instalar extensión de PostgreSQL (si la necesitas)
RUN apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql

# Optimizar Laravel
RUN php artisan optimize && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Copiar script de entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer el puerto 80
EXPOSE 80

# Ejecutar el script de entrypoint
CMD ["/entrypoint.sh"]
