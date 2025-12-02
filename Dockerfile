FROM php:8.2-apache

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
WORKDIR /var/www/html

# --- Sistema y extensiones PHP ---
RUN apt-get update && apt-get install -y \
    git unzip curl zip gnupg2 ca-certificates lsb-release \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libzip-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        mbstring \
        xml \
        gd \
        zip \
        exif \
        fileinfo \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- Composer ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# --- Copiar proyecto ---
COPY . .

# --- Apache mod_rewrite ---
RUN a2enmod rewrite
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# --- NodeJS 18 + npm ---
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && node -v \
    && npm -v

# --- Build frontend (Vite) ---
ENV CI=true
RUN npm install --production --silent --no-progress \
    && npx vite build || echo "⚠️ Advertencia: el build frontend falló, backend OK"

# ─────────────────────────────────────────────
# ✅ PERMISOS CORRECTOS PARA LARAVEL (storage / cache)
# ─────────────────────────────────────────────
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# --- EntryPoint para migraciones y seeders ---
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["sh", "-c", "php artisan migrate --force && (php artisan db:seed --class=RoleSeeder || true) && (php artisan db:seed --class=UserSeeder || true) && apache2-foreground"]
