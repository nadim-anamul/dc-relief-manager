#!/bin/bash

# DC Relief Manager - Local Test Script
# Test the deployment locally before pushing to server

set -e

echo "========================================="
echo "DC Relief Manager - Local Test"
echo "========================================="
echo ""

# Color codes
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Check Docker
if ! command -v docker &> /dev/null; then
	echo "Error: Docker is not installed."
	exit 1
fi

echo -e "${YELLOW}Creating test environment...${NC}"

# Create .env if missing
if [ ! -f .env ]; then
	cp env.example.dist .env
	echo ".env file created"
fi

# Clean start
echo "Cleaning up previous containers..."
docker compose down -v 2>/dev/null || true

# Build and start
echo "Building and starting containers..."
docker compose build
docker compose up -d

# Wait for services
echo "Waiting for services to be ready..."
sleep 15

# Check container status
echo ""
echo "Container status:"
docker compose ps

# Run migrations
echo ""
echo "Running migrations..."
docker compose exec -T app php artisan migrate --force

# Test database connection
echo ""
echo "Testing database connection..."
docker compose exec -T app php artisan migrate:status

# Show application logs
echo ""
echo "Application logs (last 20 lines):"
docker compose logs --tail=20 app

echo ""
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}Test deployment completed!${NC}"
echo -e "${GREEN}=========================================${NC}"
echo ""
echo -e "Access the application at: ${GREEN}http://localhost:8182${NC}"
echo ""
echo "Commands to try:"
echo "  - View logs:      docker compose logs -f app"
echo "  - Stop test:      docker compose down"
echo "  - Access shell:   docker compose exec app bash"
echo ""

