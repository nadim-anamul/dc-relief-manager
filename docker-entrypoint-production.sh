#!/bin/bash
set -e

echo "Starting DC Relief Manager (Production)..."

# Wait for database to be ready
echo "Waiting for database connection..."
until php artisan migrate:status &>/dev/null; do
	echo "Database not ready, waiting..."
	sleep 3
done

echo "Database is ready!"

# Run migrations (safe for production)
php artisan migrate --force

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if it doesn't exist
if [ ! -L public/storage ]; then
	php artisan storage:link
fi

# Set proper permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "Application ready!"

# Start Apache
exec apache2-foreground

