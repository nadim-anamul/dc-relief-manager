#!/bin/bash
set -e

echo "Starting DC Relief Manager..."

cd /var/www/html

# Wait for database to be ready
echo "Waiting for database..."
until php artisan migrate:status 2>/dev/null; do
	echo "Database is unavailable - sleeping"
	sleep 2
done

echo "Database is ready!"

# Ensure .env file exists
if [ ! -f .env ]; then
	echo "Creating .env file..."
	cp env.example.dist .env || cp .env.example .env
fi

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
	echo "Generating application key..."
	php artisan key:generate --force
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Link storage
php artisan storage:link || true

# Clear and optimize
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Apache..."
exec apache2-foreground

