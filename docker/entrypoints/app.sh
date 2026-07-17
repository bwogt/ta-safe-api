#!/usr/bin/env sh
set -e

#
# Composer
#
if [ ! -f vendor/autoload.php ]; then
  echo "📦 Installing Composer dependencies..."
  composer install --prefer-dist --no-interaction
else
  echo "✅ Composer dependencies already installed. Skipping."
fi

#
# APP_KEY
#
if ! grep -q "APP_KEY=base64:" .env; then
  echo "🔑 Generating APP_KEY..."
  php artisan key:generate --force
else
  echo "✅ APP_KEY already set. Skipping."
fi

#
# Database migrations and seed
#
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
