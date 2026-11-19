# Usa PHP 8.2 en modo CLI como imagen base.
FROM php:8.2-cli

# Instala las librerías del sistema necesarias y limpia los archivos de instalación.
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libpq-dev \
    unzip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilita en PHP las conexiones a MySQL y PostgreSQL, y el manejo de texto multibyte.
RUN docker-php-ext-install \
    pdo_mysql pdo_pgsql mbstring

# Copia Composer desde su imagen oficial.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece la carpeta de la aplicación y copia allí el proyecto.
WORKDIR /var/www/html
COPY . .

# Instala las dependencias de producción y optimiza el autoloader.
RUN composer install --optimize-autoloader --no-dev

# Permite que Laravel escriba en sus carpetas de almacenamiento y caché.
RUN chmod -R 775 storage bootstrap/cache

# Indica que la aplicación escucha en el puerto 8000.
EXPOSE 8000

# Crea el enlace público de almacenamiento y luego inicia el servidor de Laravel.
CMD php artisan storage:link || true \
 && php artisan serve --host=0.0.0.0 --port=8000
