#!/usr/bin/env sh
set -e

echo "🔄 Running migrations..."
php artisan migrate --force

echo "🌱 Running seeds..."
php artisan db:seed --force

echo "🚀 Starting PHP-FPM..."
php-fpm
