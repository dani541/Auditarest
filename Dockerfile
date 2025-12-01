# Usa la imagen oficial de PHP con Apache
FROM php:8.1-apache

# Variables de entorno para Composer
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instala dependencias básicas del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Copia solo los archivos necesarios primero para mejor caché
COPY composer.json composer.lock ./

# Configuración de PHP para mayor rendimiento
RUN { \
    echo 'memory_limit = -1'; \
    echo 'max_execution_time = 300'; \
    echo 'max_input_time = 300'; \
} > /usr/local/etc/php/conf.d/memory.ini

# Instala dependencias de PHP con múltiples reintentos
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts --no-progress --prefer-dist || \
    (echo "Primer intento fallido, limpiando y reintentando..." && \
     rm -rf vendor/* && \
     composer clear-cache && \
     composer install --no-dev --no-interaction --optimize-autoloader --no-scripts --no-progress --prefer-dist)

# Verifica si la instalación fue exitosa
RUN [ -f "vendor/autoload.php" ] || { echo "Error: La instalación de dependencias falló"; exit 1; }

# Copia el resto de los archivos
COPY . .

# Instala dependencias del sistema para Node.js y extensiones PHP
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    libpq-dev \
    postgresql-client \
    libonig-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# Configura extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    gd \
    zip \
    exif \
    mbstring \
    xml \
    fileinfo \
    tokenizer

# Instala Node.js y dependencias de frontend
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install --production && \
    npm run build

# Configura los permisos
RUN chown -R www-data:www-data /var/www/html/storage && \
    chmod -R 775 /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache

# Habilita mod_rewrite
RUN a2enmod rewrite

# Configuración de Apache
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

# Limpia la caché
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear

# Expone el puerto 80
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]