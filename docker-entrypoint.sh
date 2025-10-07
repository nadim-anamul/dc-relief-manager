#!/bin/bash
set -e

echo "Starting DC Relief Manager..."

cd /var/www/html

# Wait for database to be ready
echo "Waiting for database..."
timeout=120
counter=0
while [ $counter -lt $timeout ]; do
	if php artisan migrate:status 2>/dev/null; then
		echo "Database is ready!"
		break
	fi
	echo "Database is unavailable - sleeping ($counter/$timeout)"
	sleep 2
	counter=$((counter + 2))
done

if [ $counter -ge $timeout ]; then
	echo "Error: Database failed to start within $timeout seconds"
	echo "Application will continue but database operations may fail"
fi

# Ensure .env file exists
if [ ! -f .env ]; then
	echo "Creating .env file from template..."
	cp env.example.dist .env || cp .env.example .env
	
	# Override with environment variables from docker-compose if set
	if [ ! -z "$DB_HOST" ]; then
		sed -i "s/^DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
	fi
	if [ ! -z "$DB_PORT" ]; then
		sed -i "s/^DB_PORT=.*/DB_PORT=${DB_PORT}/" .env
	fi
	if [ ! -z "$DB_DATABASE" ]; then
		sed -i "s/^DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
	fi
	if [ ! -z "$DB_USERNAME" ]; then
		sed -i "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" .env
	fi
	if [ ! -z "$DB_PASSWORD" ]; then
		sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env
	fi
	if [ ! -z "$APP_ENV" ]; then
		sed -i "s/^APP_ENV=.*/APP_ENV=${APP_ENV}/" .env
	fi
	if [ ! -z "$APP_DEBUG" ]; then
		sed -i "s/^APP_DEBUG=.*/APP_DEBUG=${APP_DEBUG}/" .env
	fi
	if [ ! -z "$APP_URL" ]; then
		sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|" .env
	fi
fi

# Generate app key if not set or if .env doesn't have it
if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
	echo "Generating application key..."
	php artisan key:generate --force
fi

# Set proper permissions for .env
chmod 644 .env

# Ensure storage and cache directories exist and have correct permissions
echo "Setting up storage directories..."
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

# Fix ownership and permissions for mounted volumes
echo "Fixing permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Link storage (safe to run multiple times)
php artisan storage:link || true

# Note: Migrations are handled by deploy.sh
# Clear and optimize only if not in initial setup
if php artisan migrate:status &>/dev/null; then
	echo "Optimizing application..."
	php artisan config:cache
	php artisan route:cache
	php artisan view:cache
fi

echo "Starting Apache..."
exec apache2-foreground

