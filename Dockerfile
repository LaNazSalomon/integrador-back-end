# Etapa de build: se instala Node.js y se genera el build del frontend
FROM node:18 AS builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa de producción: se usa PHP para la aplicación Laravel
FROM php:8.2-cli

# Instalar dependencias necesarias para PHP
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
# Instalar Composer manualmente si no está disponible
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copiar el build generado desde la etapa anterior (ajusta la ruta según tu proyecto)
COPY --from=builder /app/public ./public

# Configurar Laravel (caché de config, rutas y vistas)
RUN php artisan optimize && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Exponer el puerto de PHP
EXPOSE 9000

# Comando para iniciar PHP (incluye migraciones)
CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=9000"]
