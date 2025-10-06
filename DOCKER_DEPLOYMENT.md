# Docker Deployment Guide for DC Relief Manager

This guide explains how to deploy the DC Relief Manager application using Docker on your server.

## Prerequisites

- Docker installed on your server
- Docker Compose installed on your server
- At least 2GB of available RAM
- At least 5GB of available disk space

## Quick Start

### 1. Automated Deployment (Recommended)

Run the deployment script to automatically set up everything:

```bash
./deploy.sh
```

This script will:
- Create a `.env` file with default settings
- Build the Docker containers
- Start the application and database
- Run database migrations
- Optimize the application for production

### 2. Manual Deployment

If you prefer to deploy manually:

#### Step 1: Create Environment File

Create a `.env` file in the project root:

```bash
cp .env.example .env  # If you have an example file
# OR create manually with the settings below
```

#### Step 2: Configure Environment Variables

Edit the `.env` file with your server settings:

```env
APP_NAME="DC Relief Manager"
APP_ENV=production
APP_KEY=base64:your_generated_key_here
APP_DEBUG=false
APP_URL=http://your-server-ip:8182

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=dc_relief_manager
DB_USERNAME=root
DB_PASSWORD=your_secure_password

# ... other settings
```

#### Step 3: Build and Start

```bash
# Build and start the application
docker-compose up -d --build

# Generate application key
docker-compose exec app php artisan key:generate --force

# Run database migrations
docker-compose exec app php artisan migrate --force

# Optimize for production
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

## Accessing the Application

- **Application**: `http://your-server-ip:8182`
- **Database**: `your-server-ip:3311` (MySQL)
- **Mailpit** (if enabled): `http://your-server-ip:8025`

## Configuration

### Port Configuration

The application is configured to run on port **8182** as requested. To change this:

1. Edit `docker-compose.yml`:
   ```yaml
   ports:
     - "YOUR_PORT:8182"
   ```

2. Update the Dockerfile startup script and Apache configuration

3. Update your `.env` file:
   ```env
   APP_URL=http://your-server-ip:YOUR_PORT
   ```

### Database Configuration

The MySQL database is configured with:
- **Host**: `db` (internal Docker network)
- **Port**: `3306` (internal)
- **External Port**: `3311`
- **Database**: `dc_relief_manager`
- **Username**: `root`
- **Password**: `password` (change this in production!)

### Environment Variables

Key environment variables to configure:

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_ENV` | Application environment | `production` |
| `APP_DEBUG` | Debug mode | `false` |
| `APP_URL` | Application URL | `http://localhost:8182` |
| `DB_PASSWORD` | Database password | `password` |
| `MAIL_FROM_ADDRESS` | Email sender address | `hello@example.com` |

## Useful Commands

### Container Management

```bash
# View running containers
docker-compose ps

# View application logs
docker-compose logs -f app

# View database logs
docker-compose logs -f db

# Stop all services
docker-compose down

# Restart application
docker-compose restart app

# Rebuild and restart
docker-compose up -d --build
```

### Application Management

```bash
# Access application shell
docker-compose exec app bash

# Run Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear

# Access database
docker-compose exec db mysql -u root -p dc_relief_manager
```

### Backup and Maintenance

```bash
# Backup database
docker-compose exec db mysqldump -u root -p dc_relief_manager > backup.sql

# Clear application cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Re-optimize for production
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

## Development vs Production

### Development Setup

For development with live reloading:

```bash
docker-compose -f docker-compose.dev.yml up -d --build
```

### Production Setup

For production deployment:

```bash
docker-compose up -d --build
```

## Security Considerations

1. **Change default passwords** in production
2. **Use strong database passwords**
3. **Configure proper firewall rules** for port 8182
4. **Use HTTPS** in production (consider adding a reverse proxy)
5. **Regular security updates** for Docker images
6. **Backup your database regularly**

## Troubleshooting

### Common Issues

1. **Port 8182 already in use**:
   ```bash
   # Check what's using the port
   sudo netstat -tulpn | grep :8182
   # Kill the process or use a different port
   ```

2. **Database connection failed**:
   ```bash
   # Check database logs
   docker-compose logs db
   # Restart database
   docker-compose restart db
   ```

3. **Application won't start**:
   ```bash
   # Check application logs
   docker-compose logs app
   # Rebuild containers
   docker-compose down
   docker-compose up -d --build
   ```

4. **Permission issues**:
   ```bash
   # Fix storage permissions
   docker-compose exec app chown -R www-data:www-data storage
   docker-compose exec app chmod -R 755 storage
   ```

### Logs Location

- Application logs: `storage/logs/laravel.log`
- Docker logs: `docker-compose logs app`
- Apache logs: Inside container at `/var/log/apache2/`

## File Structure

```
dc-relief-manager/
├── Dockerfile              # Main application container
├── docker-compose.yml      # Production setup
├── docker-compose.dev.yml  # Development setup
├── .dockerignore           # Docker ignore file
├── deploy.sh              # Automated deployment script
└── DOCKER_DEPLOYMENT.md   # This documentation
```

## Support

For issues or questions:
1. Check the application logs: `docker-compose logs app`
2. Verify your environment configuration
3. Ensure all required ports are available
4. Check Docker and Docker Compose versions
