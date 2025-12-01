FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

RUN a2enmod rewrite
COPY . /var/www/html
WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && npm install \
    && npm run build

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
EXPOSE 80
CMD ["apache2-foreground"]

FROM php:8.2-apache

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    default-mysql-client \
    gnupg2 \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Configura el repositorio de Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

# Instala Node.js y npm
RUN apt-get install -y nodejs

# Habilita mod_rewrite de Apache
RUN a2enmod rewrite

# Copia el contenido de la aplicación
COPY . /var/www/html

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala dependencias de Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Instala dependencias de Node.js y construye los assets
RUN npm install && npm run build

# Establece los permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expone el puerto 80
EXPOSE 80

# Comando para iniciar el servidor
CMD ["apache2-foreground"]