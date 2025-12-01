FROM php:8.1-apache

ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_ALLOW_SUPERUSER=1

# --- DEPENDENCIAS DEL SISTEMA ---
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    libpq-dev \
    postgresql-client \
    libonig-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# Node antes de Composer
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# --- CONFIG PHP & EXTENSIONES ---
RUN docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/

RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    mbstring \
    xml \
    dom \
    gd \
    zip \
    exif \
    fileinfo

# --- COMPOSER ---
COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# --- APP ---
COPY . .

RUN npm install --production && npm run build

# Permisos
RUN chown -R www-data:www-data storage bootstrap/cache

# Apache
RUN a2enmod rewrite
EXPOSE 80

CMD ["apache2-foreground"]
