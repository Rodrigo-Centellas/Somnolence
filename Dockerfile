FROM php:8.2-fpm

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Crea directorio de la app
WORKDIR /var/www

# Copia archivos del proyecto
COPY . .

# Instala dependencias de PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Da permisos (ajustar si usás laravel/sail)
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 9000
CMD ["php-fpm"]
