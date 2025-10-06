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
echo "Step 2: Building Docker images..."
docker compose build --no-cache

echo ""
echo "Step 3: Starting services..."
docker compose up -d

echo ""
echo "Step 4: Waiting for database to be ready..."
sleep 10

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
echo "  Access app shell:    docker compose exec app bash"
echo "  Run migrations:      docker compose exec app php artisan migrate"
echo "  Clear cache:         docker compose exec app php artisan cache:clear"
echo ""

