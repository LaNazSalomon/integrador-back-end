# Usa PHP 8.2 con FPM como base para mejor rendimiento en producción
FROM php:8.2-fpm

# Instalar dependencias del sistema y PHP necesarias
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    curl \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip gd \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Node.js y NPM desde NodeSource
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Configurar el directorio de trabajo
WORKDIR /app

# Copiar archivos del proyecto al contenedor
COPY . .

# Instalar Composer manualmente si no está disponible
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Laravel y optimizar la carga automática
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Instalar dependencias de NPM y construir los assets
RUN npm install --production && npm run build && npm cache clean --force

# Asegurar permisos adecuados en Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Configurar Laravel (caché de config, rutas y vistas)
RUN php artisan optimize && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Exponer el puerto de PHP-FPM
EXPOSE 9000

# Comando de inicio
CMD ["sh", "-c", "php artisan migrate --force && php-fpm"]
