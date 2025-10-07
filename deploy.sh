#!/bin/bash

# DC Relief Manager - Server Deployment Script
# This script deploys the application on a server with Docker

set -e

echo "========================================="
echo "DC Relief Manager - Deployment Script"
echo "========================================="
echo ""

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
	echo -e "${RED}Error: Docker is not installed.${NC}"
	echo "Please install Docker first: https://docs.docker.com/engine/install/"
	exit 1
fi

# Check if Docker Compose is available
if ! command -v docker compose &> /dev/null; then
	echo -e "${RED}Error: Docker Compose is not installed.${NC}"
	echo "Please install Docker Compose first: https://docs.docker.com/compose/install/"
	exit 1
fi

# Check if .env file exists
if [ ! -f .env ]; then
	echo -e "${YELLOW}Warning: .env file not found. Creating from env.example.dist...${NC}"
	if [ -f env.example.dist ]; then
		cp env.example.dist .env
		echo -e "${GREEN}.env file created. Please update it with your settings.${NC}"
	else
		echo -e "${RED}Error: env.example.dist not found. Cannot create .env file.${NC}"
		exit 1
	fi
fi

# Confirm deployment
echo -e "${YELLOW}This will deploy the application on port 8182.${NC}"
read -p "Do you want to continue? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
	echo "Deployment cancelled."
	exit 0
fi

echo ""
echo "Step 1: Stopping existing containers..."
docker compose down 2>/dev/null || true

echo ""
echo "Step 1.5: Setting up storage directories..."
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo "Step 2: Building Docker images..."
docker compose build --no-cache

echo ""
echo "Step 3: Starting services..."
docker compose up -d

echo ""
echo "Step 4: Waiting for database to be ready..."
echo "This may take a moment..."

# Wait for database health check to pass
echo "Waiting for database health check..."
timeout=120
counter=0
while [ $counter -lt $timeout ]; do
	health_status=$(docker inspect --format='{{.State.Health.Status}}' dc_relief_db 2>/dev/null || echo "starting")
	if [ "$health_status" = "healthy" ]; then
		echo "Database container is healthy!"
		break
	fi
	echo "Database health status: $health_status - waiting... ($counter/$timeout)"
	sleep 3
	counter=$((counter + 3))
done

if [ $counter -ge $timeout ]; then
	echo -e "${RED}Error: Database failed to become healthy within $timeout seconds${NC}"
	echo "Checking container logs..."
	docker compose logs db
	exit 1
fi

# Additional check: verify app can connect to database
echo "Verifying app container can connect to database..."
counter=0
timeout=60
while [ $counter -lt $timeout ]; do
	if docker compose exec -T app php artisan migrate:status &>/dev/null; then
		echo "App container successfully connected to database!"
		break
	fi
	echo "App container cannot connect yet - waiting... ($counter/$timeout)"
	sleep 2
	counter=$((counter + 2))
done

if [ $counter -ge $timeout ]; then
	echo -e "${RED}Error: App container failed to connect to database${NC}"
	echo "Checking container logs..."
	echo -e "\n${YELLOW}=== App Container Logs ===${NC}"
	docker compose logs --tail=50 app
	echo -e "\n${YELLOW}=== Database Container Logs ===${NC}"
	docker compose logs --tail=50 db
	exit 1
fi

echo ""
echo "Step 5: Running database migrations..."
docker compose exec -T app php artisan migrate --force

echo ""
echo "Step 6: Seeding database (optional)..."
read -p "Do you want to seed the database? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
	docker compose exec -T app php artisan db:seed --force
fi

echo ""
echo "Step 7: Clearing and caching configuration..."
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

echo ""
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}Deployment completed successfully!${NC}"
echo -e "${GREEN}=========================================${NC}"
echo ""
echo -e "Application URL: ${GREEN}http://YOUR_SERVER_IP:8182${NC}"
echo -e "Database Port: ${GREEN}3306${NC}"
echo ""
echo "Useful commands:"
echo "  View logs:           docker compose logs -f app"
echo "  Stop application:    docker compose down"
echo "  Restart application: docker compose restart"
echo "  Access app shell:    "
echo "  Run migrations:      docker compose exec app php artisan migrate"
echo "  Clear cache:         docker compose exec app php artisan cache:clear"
echo ""

docker compose exec app bash