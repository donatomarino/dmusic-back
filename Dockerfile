FROM php:8.2-cli

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

# Limpiar caché
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto (incluyendo archivos de música)
COPY . .

# Instalar dependencias PHP
RUN composer install --optimize-autoloader --no-dev

# Configurar permisos
RUN chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache \
    && chmod -R 755 /var/www/public

# Exponer puerto
EXPOSE 8000

# Comando de inicio con enlace simbólico en runtime
CMD php artisan storage:link && php artisan serve --host=0.0.0.0 --port=8000
