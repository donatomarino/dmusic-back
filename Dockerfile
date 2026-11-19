FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libonig-dev \
    libpq-dev \
    unzip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo_mysql pdo_pgsql mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --optimize-autoloader --no-dev

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD php artisan storage:link || true \
 && php artisan serve --host=0.0.0.0 --port=8000
