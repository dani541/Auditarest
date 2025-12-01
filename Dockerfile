# Usa la imagen oficial de PHP con Apache
FROM php:8.1-apache

# Variables de entorno para Composer
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_ALLOW_SUPERUSER=1

# --- FASE 1: Instalación de Dependencias del Sistema y Herramientas ---

# Instala dependencias del sistema y librerías necesarias para las extensiones PHP
# Se incluyen las librerías para GD, PostgreSQL, XML, y mbstring
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

# --- FASE 2: Configuración de PHP y Extensiones ---

# Configuración de PHP (Asegura límites altos para Composer)
RUN { \
    echo 'memory_limit = -1'; \
    echo 'max_execution_time = 600'; \
    echo 'max_input_time = 600'; \
} > /usr/local/etc/php/conf.d/memory.ini

# Configura la extensión GD (¡Este paso es crucial y debe ir antes de instalar GD!)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Instala todas las extensiones PHP necesarias (GD, DB, utilidades)
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

# --- FASE 3: Instalación de Dependencias PHP (Composer) ---

# Copia solo los archivos de dependencias para aprovechar la caché
COPY composer.json composer.lock ./

# Instala dependencias de PHP con reintento y opciones estándar
RUN composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist || \
    (echo "Primer intento fallido, limpiando y reintentando..." && \
     rm -rf vendor/* && \
     composer clear-cache && \
     composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist)

# Verifica si la instalación de dependencias fue exitosa
RUN [ -f "vendor/autoload.php" ] || { echo "Error: La instalación de dependencias falló."; exit 1; }

# --- FASE 4: Archivos de Aplicación y Frontend ---

# Copia el resto de los archivos de la aplicación
COPY . .

# Instala Node.js (v18 LTS) y ejecuta la compilación del frontend
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install --production && \
    npm run build

# --- FASE 5: Permisos y Configuración de Apache ---

# Configura los permisos para directorios de caché/storage (típico de Laravel)
RUN chown -R www-data:www-data /var/www/html/storage && \
    chmod -R 775 /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache

# Habilita mod_rewrite (necesario para URLs limpias)
RUN a2enmod rewrite

# Configuración de Apache para apuntar al directorio 'public'
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

# Limpia la caché del framework (asumiendo que existe un archivo 'artisan')
RUN if [ -f "artisan" ]; then \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear; \
    fi

# Expone el puerto 80
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]