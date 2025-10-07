#!/bin/bash

# DC Relief Manager - Troubleshooting Script
# Use this script to diagnose and fix common deployment issues

set -e

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "========================================="
echo "DC Relief Manager - Troubleshooting"
echo "========================================="
echo ""

# Check if containers are running
echo -e "${YELLOW}Checking container status...${NC}"
docker compose ps

echo ""
echo -e "${YELLOW}Checking .env configuration in app container...${NC}"
docker compose exec -T app cat .env | grep -E "^APP_KEY=|^APP_DEBUG=|^APP_ENV=|^DB_" || echo "Could not read .env"

echo ""
echo -e "${YELLOW}Testing database connection...${NC}"
if docker compose exec -T app php artisan migrate:status &>/dev/null; then
	echo -e "${GREEN}✓ Database connection successful${NC}"
else
	echo -e "${RED}✗ Database connection failed${NC}"
	echo "Checking database container logs..."
	docker compose logs --tail=20 db
fi

echo ""
echo -e "${YELLOW}Clearing all caches...${NC}"
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear
docker compose exec -T app php artisan route:clear
docker compose exec -T app php artisan view:clear
echo -e "${GREEN}✓ Caches cleared${NC}"

echo ""
echo -e "${YELLOW}Checking storage permissions...${NC}"
docker compose exec -T app ls -la storage/logs | head -n 5

echo ""
echo -e "${YELLOW}Fixing permissions...${NC}"
docker compose exec -T app chown -R www-data:www-data storage bootstrap/cache
docker compose exec -T app chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✓ Permissions fixed${NC}"

echo ""
echo -e "${YELLOW}Checking recent application logs...${NC}"
docker compose exec -T app tail -n 30 storage/logs/laravel.log 2>/dev/null || echo "No log file found yet"

echo ""
echo -e "${YELLOW}Testing application response...${NC}"
response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8182)
if [ "$response" = "200" ] || [ "$response" = "302" ]; then
	echo -e "${GREEN}✓ Application responding (HTTP $response)${NC}"
else
	echo -e "${RED}✗ Application error (HTTP $response)${NC}"
	echo "Checking Apache error logs..."
	docker compose exec -T app tail -n 20 /var/log/apache2/error.log 2>/dev/null || echo "Could not read Apache logs"
fi

echo ""
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}Troubleshooting Complete${NC}"
echo -e "${GREEN}=========================================${NC}"
echo ""
echo "If issues persist:"
echo "  1. Check logs: docker compose logs -f app"
echo "  2. Restart containers: docker compose restart"
echo "  3. Fresh deployment: docker compose down -v && ./deploy.sh"
echo ""

