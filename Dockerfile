# Usa PHP 8.2 como imagen base
FROM php:8.2-cli

# Instalar dependencias necesarias y Node.js desde NodeSource
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    curl \
    git \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurar el directorio de trabajo
WORKDIR /app
COPY . .

# Instalar Composer manualmente si no está disponible
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Laravel y NPM
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Verificar que Node.js y npm están instalados correctamente
RUN node -v && npm -v

# Instalar dependencias de NPM y construir los assets
RUN npm install --production && npm run build

# Configurar Laravel (caché de config, rutas y vistas)
RUN php artisan optimize && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Asegurar permisos adecuados en Laravel
RUN chmod -R 775 storage bootstrap/cache

# Exponer el puerto de PHP
EXPOSE 9000

# Comando para iniciar PHP (incluye migraciones)
CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=9000"]
