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

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# --- CONFIGURACIÓN PHP ---
RUN { \
    echo 'memory_limit = -1'; \
    echo 'max_execution_time = 600'; \
    echo 'max_input_time = 600'; \
} > /usr/local/etc/php/conf.d/memory.ini

# --- EXTENSIONES PHP CORRECTAS ---
# Configurar GD con rutas correctas
RUN docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/

# Instalar extensiones
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    mbstring \
    xml \
    gd \
    zip \
    exif \
    fileinfo

# --- DEPENDENCIAS PHP (COMPOSER) ---
COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist || \
    (echo "Primer intento fallido, limpiando y reintentando..." && \
     rm -rf vendor/* && \
     composer clear-cache && \
     composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist)

RUN [ -f "vendor/autoload.php" ] || { echo "Error: La instalación de dependencias falló."; exit 1; }

# --- ARCHIVOS DE LA APLICACIÓN ---
COPY . .

# --- NODEJS + BUILD FRONTEND ---
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install --production && \
    npm run build

# --- PERMISOS ---
RUN chown -R www-data:www-data /var/www/html/storage && \
    chmod -R 775 /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache

# --- CONFIG APACHE ---
RUN a2enmod rewrite

RUN echo '<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
        FallbackResource /index.php\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
    <Directory /var/www/html/public>\n\
        RewriteEngine On\n\
        RewriteCond %{REQUEST_FILENAME} !-d\n\
        RewriteCond %{REQUEST_FILENAME} !-f\n\
        RewriteRule ^ index.php [L]\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# --- LIMPIEZA CACHÉ LARAVEL ---
RUN if [ -f "artisan" ]; then \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear; \
    fi

EXPOSE 80

CMD ["apache2-foreground"]
