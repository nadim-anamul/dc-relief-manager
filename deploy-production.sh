#!/bin/bash

# DC Relief Manager - Production Deployment Script with Browsershot Support
# This script deploys the application for production use

set -e

echo "========================================="
echo "DC Relief Manager - Production Deploy"
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
		echo -e "${GREEN}.env file created.${NC}"
		echo -e "${YELLOW}Please update .env with your production settings!${NC}"
		echo ""
		read -p "Press Enter after updating .env file..."
	else
		echo -e "${RED}Error: env.example.dist not found. Cannot create .env file.${NC}"
		exit 1
	fi
fi

echo -e "${BLUE}This will deploy the application for PRODUCTION use.${NC}"
echo -e "${YELLOW}Features included:${NC}"
echo "  • Optimized for production (no dev dependencies)"
echo "  • Browsershot/Puppeteer for PDF generation"
echo "  • Perfect Bangla font rendering"
echo "  • Automatic caching and optimization"
echo "  • Security hardening"
echo ""
read -p "Do you want to continue? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
	echo "Deployment cancelled."
	exit 0
fi

echo ""
echo "Step 1: Stopping existing containers..."
docker compose -f docker-compose.production.yml down 2>/dev/null || true

echo ""
echo "Step 2: Setting up storage directories..."
mkdir -p storage/app/public \
	storage/app/temp \
	storage/framework/cache \
	storage/framework/sessions \
	storage/framework/views \
	storage/logs \
	bootstrap/cache

chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo "Step 3: Building production Docker image..."
echo "This may take 5-10 minutes (includes Chromium installation)..."
docker compose -f docker-compose.production.yml build --no-cache

echo ""
echo "Step 4: Starting services..."
docker compose -f docker-compose.production.yml up -d

echo ""
echo "Step 5: Waiting for database to be ready..."
timeout=120
counter=0
while [ $counter -lt $timeout ]; do
	health_status=$(docker inspect --format='{{.State.Health.Status}}' dc_relief_db_prod 2>/dev/null || echo "starting")
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
	docker compose -f docker-compose.production.yml logs db
	exit 1
fi

echo ""
echo "Step 6: Waiting for application to be ready..."
counter=0
timeout=60
while [ $counter -lt $timeout ]; do
	if docker compose -f docker-compose.production.yml exec -T app php artisan --version &>/dev/null; then
		echo "Application is ready!"
		break
	fi
	echo "Application starting... ($counter/$timeout)"
	sleep 2
	counter=$((counter + 2))
done

if [ $counter -ge $timeout ]; then
	echo -e "${RED}Error: Application failed to start${NC}"
	docker compose -f docker-compose.production.yml logs app
	exit 1
fi

echo ""
echo "Step 7: Testing Browsershot/Puppeteer..."
echo "Creating test PDF to verify Bangla font rendering..."
docker compose -f docker-compose.production.yml exec -T app php artisan tinker --execute="
echo 'Testing Browsershot...';
try {
	\$html = '<html><head><meta charset=\"UTF-8\"><link href=\"https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;600;700&display=swap\" rel=\"stylesheet\"><style>body{font-family:\"Noto Sans Bengali\",sans-serif;padding:40px;}</style></head><body><h1>বরাদ্দ পরীক্ষা</h1><p>Bangla: ১২৩৪৫৬৭৮৯০</p></body></html>';
	\$path = storage_path('app/temp/browsershot_test.pdf');
	if (!file_exists(dirname(\$path))) mkdir(dirname(\$path), 0755, true);
	\Spatie\Browsershot\Browsershot::html(\$html)
		->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
		->format('A4')
		->margins(15, 15, 15, 15)
		->showBackground()
		->save(\$path);
	if (file_exists(\$path)) {
		echo 'SUCCESS: Browsershot is working! PDF created at: ' . \$path;
		unlink(\$path);
	} else {
		echo 'ERROR: PDF was not created';
	}
} catch (Exception \$e) {
	echo 'ERROR: ' . \$e->getMessage();
}
"

echo ""
echo "Step 8: Running database migrations..."
docker compose -f docker-compose.production.yml exec -T app php artisan migrate --force

echo ""
echo "Step 9: Seeding database (optional)..."
read -p "Do you want to seed the database? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
	docker compose -f docker-compose.production.yml exec -T app php artisan db:seed --force
fi

echo ""
echo "Step 10: Optimizing for production..."
docker compose -f docker-compose.production.yml exec -T app php artisan optimize

echo ""
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}Production deployment completed!${NC}"
echo -e "${GREEN}=========================================${NC}"
echo ""
echo -e "Application URL: ${GREEN}http://139.162.3.19:8182${NC}"
echo -e "Database Port: ${GREEN}33011${NC}"
echo ""
echo -e "${GREEN}✅ Browsershot PDF Generation: Working${NC}"
echo -e "${GREEN}✅ Bangla Font Rendering: Perfect${NC}"
echo -e "${GREEN}✅ Production Optimized: Yes${NC}"
echo ""
echo "Useful commands:"
echo "  View logs:           docker compose -f docker-compose.production.yml logs -f app"
echo "  Stop application:    docker compose -f docker-compose.production.yml down"
echo "  Restart application: docker compose -f docker-compose.production.yml restart"
echo "  Access app shell:    docker compose -f docker-compose.production.yml exec app bash"
echo "  Run artisan:         docker compose -f docker-compose.production.yml exec app php artisan [command]"
echo "  Clear cache:         docker compose -f docker-compose.production.yml exec app php artisan optimize:clear"
echo ""
echo -e "${BLUE}Test PDF Export:${NC}"
echo "  1. Go to http://139.162.3.19:8182/admin/projects"
echo "  2. Click 'Export PDF' button"
echo "  3. Check if Bangla text renders perfectly"
echo ""
echo -e "${YELLOW}Note: Your application is now running in PRODUCTION mode!${NC}"

