# Usa la imagen oficial de PHP con Apache
FROM php:8.1-apache

# Variables de entorno para Composer
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_ALLOW_SUPERUSER=1

# --- FASE 1: Preparación del Entorno y Herramientas ---

# Instala dependencias básicas del sistema y librerías necesarias para extensiones PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
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

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Configuración de PHP (Aumenta los límites por si la instalación es muy grande)
RUN { \
    echo 'memory_limit = -1'; \
    echo 'max_execution_time = 600'; \
    echo 'max_input_time = 600'; \
} > /usr/local/etc/php/conf.d/memory.ini

# Configura e instala extensiones PHP ESENCIALES que Composer necesita para la instalación
# Esto debe ir ANTES de composer install
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

# --- FASE 2: Instalación de Dependencias PHP (Composer) ---

# Copia solo los archivos necesarios primero para mejor caché (Layer Caching)
COPY composer.json composer.lock ./

# Instala dependencias de PHP con reintento mejorado para diagnóstico
# Nota: Quitamos --no-progress y --no-scripts del comando final para asegurar la visibilidad del error
RUN composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist || \
    (echo "Primer intento fallido, limpiando y reintentando..." && \
     rm -rf vendor/* && \
     composer clear-cache && \
     composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist)

# Verifica si la instalación fue exitosa
RUN [ -f "vendor/autoload.php" ] || { echo "Error: La instalación de dependencias falló, revisa la salida anterior."; exit 1; }

# --- FASE 3: Archivos de Aplicación y Frontend ---

# Copia el resto de los archivos de la aplicación
COPY . .

# Instala Node.js y dependencias de frontend
# Node.js 18 (LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install --production && \
    npm run build

# --- FASE 4: Configuración Final y Limpieza ---

# Configura los permisos para Laravel/Symfony/etc.
RUN chown -R www-data:www-data /var/www/html/storage && \
    chmod -R 775 /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache

# Habilita mod_rewrite
RUN a2enmod rewrite

# Configuración de Apache para Laravel/Frameworks (Apuntando a public)
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

# Limpia la caché (Esto puede necesitar las variables de entorno de la app, hazlo al final)
# Esto asume que tienes 'artisan' en tu proyecto.
RUN if [ -f "artisan" ]; then \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear; \
    fi

# Expone el puerto 80
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]