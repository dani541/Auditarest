#!/bin/sh
set -e

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Sembrando roles..."
php artisan db:seed --class=RoleSeeder

echo "Sembrando usuarios..."
php artisan db:seed --class=UserSeeder

echo "Iniciando Apache..."
exec apache2-foreground
