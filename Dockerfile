FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    curl \
    git \
    libmariadb-dev \
    && docker-php-ext-install pdo pdo_mysql

WORKDIR /app
COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN php artisan optimize && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

EXPOSE 9000

CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=9000"]
