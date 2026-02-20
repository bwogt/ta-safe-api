#!/usr/bin/env sh
set -e

echo "🔄 Running migrations..."
php artisan migrate:fresh --env=testing

echo "🚀 Starting PHP-FPM..."
php-fpm
