#!/bin/bash

# DC Relief Manager Deployment Script
# This script helps deploy the application using Docker

set -e

echo "🚀 Starting DC Relief Manager deployment..."

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cat > .env << EOF
APP_NAME="DC Relief Manager"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost:8182

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=dc_relief_manager
DB_USERNAME=root
DB_PASSWORD=password

BROADCAST_DRIVER=file
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="\${PUSHER_HOST}"
VITE_PUSHER_PORT="\${PUSHER_PORT}"
VITE_PUSHER_SCHEME="\${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"
EOF
    echo "✅ .env file created"
else
    echo "✅ .env file already exists"
fi

# Stop existing containers if they exist
echo "🛑 Stopping existing containers..."
docker compose down 2>/dev/null || true

# Build and start the application
echo "🔨 Building and starting the application..."
docker compose up -d --build

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 30

# Generate application key if not set
echo "🔑 Generating application key..."
docker compose exec -T app php artisan key:generate --force

# Run database migrations
echo "🗄️  Running database migrations..."
docker compose exec -T app php artisan migrate --force

# Clear and cache configuration
echo "⚙️  Optimizing application..."
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:clear
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:clear
docker compose exec -T app php artisan view:cache

echo "✅ Deployment completed successfully!"
echo ""
echo "🌐 Application is now available at: http://localhost:8182"
echo "📧 Mailpit (for email testing) is available at: http://localhost:8025"
echo "🗄️  Database is available at: localhost:3311"
echo ""
echo "📋 Useful commands:"
echo "  - View logs: docker compose logs -f app"
echo "  - Stop application: docker compose down"
echo "  - Restart application: docker compose restart"
echo "  - Access application shell: docker compose exec app bash"
echo "  - Access database: docker compose exec db mysql -u root -p dc_relief_manager"
