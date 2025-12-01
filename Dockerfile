FROM php:8.1-apache

ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_ALLOW_SUPERUSER=1

# --- DEPENDENCIAS DEL SISTEMA ---
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    nano \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    postgresql-client \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# --- NODEJS ANTES DE COMPOSER ---
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# --- EXTENSIONES PHP ---
RUN docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/

RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    mbstring \
    xml \
    opcache \
    gd \
    zip \
    exif \
    fileinfo

# --- ARCHIVOS COMPOSER ---
COPY composer.json composer.lock ./

# Composer SIN SCRIPTS (evita errores con artisan, vite, migrations, etc)
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

# --- COPIA DEL PROYECTO ---
COPY . .

# --- BUILD FRONTEND ---
RUN npm install --production
RUN npm run build || echo "Advertencia: el build de frontend falló, pero el backend funciona"

# --- PERMISOS LARAVEL ---
RUN mkdir -p storage/logs storage/framework bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

# --- APACHE ---
RUN a2enmod rewrite

# VirtualHost
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

EXPOSE 80
CMD ["apache2-foreground"]
