#!/bin/bash

# DC Relief Manager - Minimal Development Deployment Script
# This script sets up the application for development with live code changes

set -e

echo "========================================="
echo "DC Relief Manager - Development Setup"
echo "========================================="
echo ""

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
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

echo -e "${BLUE}This will set up the application for development with live code changes.${NC}"
echo -e "${BLUE}Code changes will be reflected immediately without rebuilding containers.${NC}"
echo ""
read -p "Do you want to continue? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
	echo "Setup cancelled."
	exit 0
fi

echo ""
echo "Step 1: Stopping existing containers..."
docker compose -f docker-compose.dev.yml down 2>/dev/null || true

echo ""
echo "Step 2: Setting up storage directories..."
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo "Step 3: Building development Docker image..."
docker compose -f docker-compose.dev.yml build --no-cache

echo ""
echo "Step 4: Starting services..."
docker compose -f docker-compose.dev.yml up -d

echo ""
echo "Step 5: Waiting for database to be ready..."
echo "This may take a moment..."

# Wait for database health check to pass
echo "Waiting for database health check..."
timeout=120
counter=0
while [ $counter -lt $timeout ]; do
	health_status=$(docker inspect --format='{{.State.Health.Status}}' dc_relief_db_dev 2>/dev/null || echo "starting")
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
	docker compose -f docker-compose.dev.yml logs db
	exit 1
fi

# Additional check: verify app can connect to database
echo "Verifying app container can connect to database..."
counter=0
timeout=60
while [ $counter -lt $timeout ]; do
	if docker compose -f docker-compose.dev.yml exec -T app php artisan migrate:status &>/dev/null; then
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
	docker compose -f docker-compose.dev.yml logs --tail=50 app
	echo -e "\n${YELLOW}=== Database Container Logs ===${NC}"
	docker compose -f docker-compose.dev.yml logs --tail=50 db
	exit 1
fi

echo ""
echo "Step 6: Running database migrations..."
docker compose -f docker-compose.dev.yml exec -T app php artisan migrate --force

echo ""
echo "Step 7: Seeding database (optional)..."
read -p "Do you want to seed the database? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
	docker compose -f docker-compose.dev.yml exec -T app php artisan db:seed --force
fi

echo ""
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}Development setup completed successfully!${NC}"
echo -e "${GREEN}=========================================${NC}"
echo ""
echo -e "Application URL: ${GREEN}http://localhost:8182${NC}"
echo -e "Database Port: ${GREEN}3306${NC}"
echo ""
echo -e "${BLUE}Development Features:${NC}"
echo -e "  • Code changes are reflected immediately"
echo -e "  • No need to rebuild containers for code changes"
echo -e "  • Debug mode is enabled"
echo -e "  • All caches are cleared automatically"
echo ""
echo "Useful commands:"
echo "  View logs:           docker compose -f docker-compose.dev.yml logs -f app"
echo "  Stop application:    docker compose -f docker-compose.dev.yml down"
echo "  Restart application: docker compose -f docker-compose.dev.yml restart"
echo "  Access app shell:    docker compose -f docker-compose.dev.yml exec app bash"
echo "  Run migrations:      docker compose -f docker-compose.dev.yml exec app php artisan migrate"
echo "  Clear cache:         docker compose -f docker-compose.dev.yml exec app php artisan cache:clear"
echo ""
echo -e "${YELLOW}Note: Make code changes directly in your editor - they will be reflected immediately!${NC}"
