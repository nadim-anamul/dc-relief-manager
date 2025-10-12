# DC Relief Manager - Deployment Guide

This guide will help you deploy the DC Relief Manager application on a server using Docker.

## Prerequisites

- A server with Ubuntu 20.04 or later (or any Linux distribution with Docker support)
- Docker and Docker Compose installed
- Minimum 2GB RAM, 2 CPU cores, 10GB disk space
- Port 8182 available for the application
- Port 3306 available for MySQL (or you can change it)

## Minimal Development Setup (Recommended)

For development with live code changes, use the minimal setup:

### 1. Quick Development Setup

```bash
# Clone or upload the project
git clone <your-repository-url> dc-relief-manager
cd dc-relief-manager

# Make deployment script executable
chmod +x deploy-dev.sh

# Run minimal development setup
./deploy-dev.sh
```

This setup provides:
- **Live code changes**: Edit code directly - changes are reflected immediately
- **No rebuilding**: Code changes don't require container rebuilds
- **Development optimizations**: Debug mode, cache clearing, hot reloading
- **Minimal resources**: Faster startup and lower resource usage

### 2. Development Features

- Code changes are reflected immediately without rebuilding containers
- Debug mode enabled by default
- Automatic cache clearing for development
- Volume mounts for live code synchronization
- Optimized for development workflow

### 3. Development Commands

```bash
# Start development environment
docker compose -f docker-compose.dev.yml up -d

# View logs
docker compose -f docker-compose.dev.yml logs -f app

# Stop development environment
docker compose -f docker-compose.dev.yml down

# Access app shell
docker compose -f docker-compose.dev.yml exec app bash

# Clear caches
docker compose -f docker-compose.dev.yml exec app php artisan cache:clear
```

## Production Deployment

For production deployment, use the standard setup:

## Quick Start

### 1. Install Docker (if not already installed)

```bash
# Update package index
sudo apt-get update

# Install required packages
sudo apt-get install -y ca-certificates curl gnupg lsb-release

# Add Docker's official GPG key
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg

# Set up Docker repository
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Install Docker Engine
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# Add your user to docker group
sudo usermod -aG docker $USER

# Apply group changes (or logout/login)
newgrp docker
```

### 2. Clone or Upload the Project

```bash
# Clone from repository
git clone <your-repository-url> dc-relief-manager
cd dc-relief-manager

# OR upload files to server
# scp -r /local/path user@server:/path/to/dc-relief-manager
```

### 3. Configure Environment

```bash
# Copy and edit environment file
cp env.example.dist .env
nano .env  # or use vim, vi, etc.

# Update these important settings:
# - APP_URL=http://YOUR_SERVER_IP:8182
# - DB_PASSWORD=your_secure_password
# - Update other settings as needed
```

### 4. Deploy

```bash
# Make deployment script executable
chmod +x deploy.sh

# Run deployment
./deploy.sh
```

The script will:
- Build Docker images
- Start MySQL and Application containers
- Run database migrations
- Optionally seed the database
- Optimize the application

### 5. Access the Application

Once deployed, access the application at:
```
http://YOUR_SERVER_IP:8182
```

## Manual Deployment Steps

If you prefer manual deployment:

```bash
# 1. Stop existing containers
docker compose down

# 2. Build images
docker compose build --no-cache

# 3. Start services
docker compose up -d

# 4. Wait for database (about 10 seconds)
sleep 10

# 5. Run migrations
docker compose exec app php artisan migrate --force

# 6. (Optional) Seed database
docker compose exec app php artisan db:seed --force

# 7. Optimize application
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

## Development vs Production

### Development Setup (`docker-compose.dev.yml`)
- **Volume mounts**: Entire application directory mounted for live code changes
- **Debug mode**: Enabled by default
- **Cache clearing**: Automatic cache clearing for development
- **Dependencies**: Installed on container startup
- **Optimizations**: Disabled for faster development

### Production Setup (`docker-compose.yml`)
- **Optimized builds**: Pre-built containers with optimized assets
- **Security**: Debug mode disabled
- **Performance**: Caching enabled for production
- **Dependencies**: Pre-installed in container image
- **Stability**: Optimized for production workloads

## Configuration

### Changing Ports

To change the application port (default: 8182):

1. Edit `docker-compose.yml`:
```yaml
ports:
  - "YOUR_PORT:8182"  # Change YOUR_PORT to desired port
```

2. Edit `.env`:
```
APP_URL=http://YOUR_SERVER_IP:YOUR_PORT
```

3. Rebuild and restart:
```bash
docker compose down
docker compose up -d --build
```

### Database Configuration

The database credentials are set in `docker-compose.yml`. To change them:

1. Edit `docker-compose.yml` (both app and db services)
2. Update `.env` file to match
3. Rebuild and restart

### Environment Variables

Key environment variables in `.env`:

- `APP_NAME`: Application name
- `APP_ENV`: Environment (production/local)
- `APP_DEBUG`: Debug mode (false for production)
- `APP_URL`: Full application URL
- `DB_*`: Database credentials
- Other Laravel configuration

## Useful Commands

### Container Management
```bash
# View running containers
docker compose ps

# View logs
docker compose logs -f app
docker compose logs -f db

# Stop containers
docker compose down

# Restart containers
docker compose restart

# Rebuild and restart
docker compose up -d --build
```

### Application Commands
```bash
# Access application shell
docker compose exec app bash

# Run artisan commands
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear

# Create admin user (example)
docker compose exec app php artisan tinker
```

### Database Access
```bash
# Access MySQL shell
docker compose exec db mysql -u dc_user -p dc_relief_manager

# Backup database
docker compose exec db mysqldump -u dc_user -p dc_relief_manager > backup.sql

# Restore database
docker compose exec -T db mysql -u dc_user -p dc_relief_manager < backup.sql
```

## Firewall Configuration

If using UFW firewall:

```bash
# Allow application port
sudo ufw allow 8182/tcp

# Allow SSH (if not already allowed)
sudo ufw allow 22/tcp

# Enable firewall
sudo ufw enable
```

## SSL/HTTPS Setup (Production)

For production with SSL, consider using Nginx as a reverse proxy:

1. Install Nginx:
```bash
sudo apt-get install nginx certbot python3-certbot-nginx
```

2. Create Nginx configuration:
```bash
sudo nano /etc/nginx/sites-available/dc-relief
```

3. Add configuration:
```nginx
server {
    listen 80;
    server_name your-domain.com;

    location / {
        proxy_pass http://localhost:8182;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

4. Enable site and get SSL:
```bash
sudo ln -s /etc/nginx/sites-available/dc-relief /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
sudo certbot --nginx -d your-domain.com
```

## Troubleshooting

### Application not accessible
- Check if containers are running: `docker compose ps`
- Check logs: `docker compose logs app`
- Verify port 8182 is not in use: `sudo lsof -i :8182`
- Check firewall: `sudo ufw status`

### Database connection errors
- Ensure database container is running: `docker compose ps`
- Check database logs: `docker compose logs db`
- Verify credentials in `.env` match `docker-compose.yml`

### Permission errors
- Run: `docker compose exec app chown -R www-data:www-data storage bootstrap/cache`
- Run: `docker compose exec app chmod -R 755 storage bootstrap/cache`

### Migration errors
- Check database connection: `docker compose exec app php artisan migrate:status`
- Reset migrations: `docker compose exec app php artisan migrate:fresh`

## Updates and Maintenance

### Updating the Application
```bash
# Pull latest code
git pull origin main  # or your branch

# Rebuild and restart
docker compose down
docker compose up -d --build

# Run migrations
docker compose exec app php artisan migrate --force

# Clear cache
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

### Backup Strategy
1. Regular database backups (automated via cron)
2. Backup storage directory
3. Keep .env file secure
4. Version control for code

## Security Recommendations

1. **Change default passwords** in `docker-compose.yml`
2. **Set APP_DEBUG=false** in production
3. **Use strong APP_KEY** (auto-generated)
4. **Keep Docker and system updated**
5. **Use firewall** to restrict access
6. **Implement SSL/HTTPS** for production
7. **Regular backups** of database and files
8. **Monitor logs** for suspicious activity

## Support

For issues or questions:
- Check logs: `docker compose logs -f`
- Review Laravel logs: `docker compose exec app tail -f storage/logs/laravel.log`
- Check application health: Access `/` endpoint

---

## Quick Reference

### Development (Live Code Changes)
```bash
# Setup
./deploy-dev.sh

# Daily development
docker compose -f docker-compose.dev.yml up -d
# Edit code in your editor - changes appear immediately!

# Stop
docker compose -f docker-compose.dev.yml down
```

### Production (Optimized)
```bash
# Setup
./deploy.sh

# Daily production
docker compose up -d

# Stop
docker compose down
```

### Key Differences
| Feature | Development | Production |
|---------|-------------|------------|
| Code Changes | Immediate (no rebuild) | Requires rebuild |
| Debug Mode | Enabled | Disabled |
| Caching | Disabled | Enabled |
| Build Time | Fast | Slower (optimized) |
| Resource Usage | Lower | Higher |
| Security | Development | Production |

---

**Note**: This deployment uses Docker for easy setup and portability. For high-traffic production environments, consider additional optimizations like Redis caching, queue workers, and load balancing.

