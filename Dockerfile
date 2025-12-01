FROM php:8.2-apache

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
WORKDIR /var/www/html

# Sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    git unzip curl zip gnupg2 ca-certificates lsb-release \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libzip-dev libonig-dev libxml2-dev libpq-dev postgresql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql mbstring xml gd zip exif fileinfo \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar composer.json y composer.lock
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# Copiar resto del proyecto
COPY . .

# Habilitar Apache mod_rewrite
RUN a2enmod rewrite

# NodeJS 18 + npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && node -v \
    && npm -v

# Build frontend
ENV CI=true
RUN npm install --production --silent --no-progress \
    && npm run build || echo "Advertencia: build frontend falló, backend funciona"

# Permisos Laravel
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
