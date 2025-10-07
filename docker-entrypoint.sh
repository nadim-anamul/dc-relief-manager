#!/bin/bash
set -e

echo "Starting DC Relief Manager..."

cd /var/www/html

# CRITICAL: Create .env file FIRST before any Laravel commands
echo "Checking for .env file..."
if [ ! -f .env ]; then
	echo "Creating .env file from template..."
	if [ -f env.example.dist ]; then
		cp env.example.dist .env
		echo ".env created from env.example.dist"
	elif [ -f .env.example ]; then
		cp .env.example .env
		echo ".env created from .env.example"
	else
		echo "ERROR: No env template file found!"
		exit 1
	fi
else
	echo ".env file already exists"
fi

# Always update environment variables from docker-compose
echo "Updating .env with environment variables..."
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

# CRITICAL: Always ensure APP_KEY is set before anything else
if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
	echo "Generating application key..."
	php artisan key:generate --force
fi

# Set proper permissions for .env
chmod 644 .env

# Now wait for database to be ready
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
else
	echo "Database not ready or migrations not run. Clearing caches to prevent errors..."
	php artisan config:clear 2>/dev/null || true
	php artisan cache:clear 2>/dev/null || true
	php artisan route:clear 2>/dev/null || true
	php artisan view:clear 2>/dev/null || true
fi

# Ensure APP_KEY is set
if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
	echo "APP_KEY not found, generating..."
	php artisan key:generate --force
fi

echo "Starting Apache..."
exec apache2-foreground

