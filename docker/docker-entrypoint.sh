#!/bin/sh
set -e

echo "Ejecutando migraciones..."
php artisan migrate --force

# Only run seeders in non-production or when explicitly allowed
if [ "$APP_ENV" != "production" ] || [ "$SEED_DATABASE" = "true" ]; then
    echo "Sembrando roles..."
    php artisan db:seed --class=RoleSeeder

    echo "Sembrando usuarios..."
    php artisan db:seed --class=UserSeeder
else
    echo "Omitiendo seeders en producción. Para habilitar, establece SEED_DATABASE=true"
fi

echo "Iniciando Apache..."
exec apache2-foreground