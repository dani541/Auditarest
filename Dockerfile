FROM php:8.1-apache

# Instala dependencias del sistema
# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libpq-dev \        # Añade esta línea
    postgresql-client  # Opcional: para comandos de cliente

# Instala extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) pdo pdo_pgsql gd zip exif  # Reordenado


# Habilita mod_rewrite
RUN a2enmod rewrite

# Crea directorio para configuración de Apache
RUN mkdir -p /etc/apache2/sites-available/

# Crea el archivo de configuración directamente
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

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Copia solo los archivos necesarios
COPY composer.json composer.lock ./
COPY database/ database/
COPY resources/ resources/
COPY routes/ routes/
COPY config/ config/
COPY app/ app/
COPY public/ public/
COPY bootstrap/ bootstrap/

# Instala dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build

# Copia el resto de los archivos
COPY . .

# Configura los permisos
RUN chown -R www-data:www-data /var/www/html/storage
RUN chmod -R 775 /var/www/html/storage

# Expone el puerto 80
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]