FROM php:8.1-apache

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
    npm

# Instala extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql zip exif

# Habilita mod_rewrite
RUN a2enmod rewrite

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

# Configura Apache
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Expone el puerto 80
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]