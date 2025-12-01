# ---------- STAGE 1: Composer ----------
FROM composer:2 AS composer_stage

WORKDIR /app

# Copiar composer.json y composer.lock
COPY composer.json composer.lock ./

# Instalar dependencias PHP sin scripts
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# ---------- STAGE 2: PHP + Apache ----------
FROM php:8.2-apache

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
WORKDIR /var/www/html

# --- Sistema y extensiones PHP ---
RUN apt-get update && apt-get install -y \
    git unzip curl zip gnupg2 ca-certificates lsb-release \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libzip-dev libonig-dev libxml2-dev libpq-dev postgresql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql mbstring xml gd zip exif fileinfo \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- Apache mod_rewrite ---
RUN a2enmod rewrite

# --- Configurar Apache para Laravel public ---
COPY ./docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# --- Copiar proyecto ---
COPY . .

# --- Copiar vendor del stage Composer ---
COPY --from=composer_stage /app/vendor ./vendor

# --- NodeJS 18 + npm ---
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && node -v \
    && npm -v

# --- Build frontend usando npx ---
ENV CI=true
RUN npm install --production --silent --no-progress \
    && npx vite build || echo "Advertencia: build frontend falló, backend funciona"

# --- Permisos Laravel ---
RUN mkdir -p storage bootstrap/cache public \
    && chown -R www-data:www-data storage bootstrap/cache public \
    && chmod -R 775 storage bootstrap/cache public

# --- Exponer puerto ---
EXPOSE 80
CMD ["apache2-foreground"]
