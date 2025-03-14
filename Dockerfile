# Usa PHP 8.2 como imagen base
FROM php:8.2-cli

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    curl \
    git \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql \
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

# Ejecutar migraciones manualmente para evitar errores
RUN php artisan migrate --force || true

# Crear symlink para almacenamiento
RUN php artisan storage:link

# Exponer el puerto de PHP (Railway lo asigna dinámicamente, pero 8080 es común)
EXPOSE 8080

# Comando para iniciar PHP en el puerto 8080
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
