#!/usr/bin/env sh
set -e

echo "🔍 Checking if database is initialized..."

if ! php artisan migrate:status > /dev/null 2>&1; then
  echo "⚙️ First-time setup detected. Running migrations and seed..."
  php artisan migrate --force
  php artisan db:seed --force
else
  echo "✅ Database already initialized. Skipping migrations."
fi

echo "🚀 Starting PHP-FPM..."
php-fpm
