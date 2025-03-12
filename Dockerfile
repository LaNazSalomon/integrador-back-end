# Usa PHP 8.2 como imagen base
FROM php:8.2-cli

# Instalar dependencias necesarias y Node.js desde NodeSource
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    curl \
    git \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install pdo pdo_mysql

# Configurar el directorio de trabajo
WORKDIR /app
COPY . .

# Instalar Composer manualmente si no está disponible
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Laravel y NPM
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Opcional: Verificar versión de npm
RUN npm -v

# Instalar dependencias NPM y ejecutar build
RUN npm install && npm run build && npm cache clean --force

# Configurar Laravel (caché de config, rutas y vistas)
RUN php artisan optimize && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Exponer el puerto de PHP
EXPOSE 9000

# Comando para iniciar PHP (incluye migraciones)
CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=9000"]
