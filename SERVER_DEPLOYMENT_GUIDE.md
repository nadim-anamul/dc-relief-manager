# DC Relief Manager - Server Deployment Guide

## Quick Start

```bash
# Clone the repository
git clone <your-repo-url> dc-relief-manager
cd dc-relief-manager

# Run deployment script
./deploy.sh
```

## Common Issues and Solutions

### 1. **500 Server Error (Even with APP_DEBUG=true)**

**Symptoms:**
- Blank white page or generic 500 error
- No detailed error message displayed

**Causes:**
- Cached configuration with wrong settings
- Missing APP_KEY
- Database connection issues
- Permission problems

**Solutions:**

#### Option A: Use the Troubleshooting Script
```bash
./troubleshoot.sh
```

#### Option B: Manual Fix
```bash
# Clear all caches
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear

# Fix permissions
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache

# Restart containers
docker compose restart
```

#### Option C: Fresh Deployment
```bash
# Remove everything and start fresh
docker compose down -v
./deploy.sh
```

### 2. **Database Connection Issues**

**Symptoms:**
```
SQLSTATE[HY000] [1045] Access denied for user 'dc_user'
```

**Solution:**
The issue is usually an old database volume with different credentials.

```bash
# Stop and remove volumes
docker compose down -v

# Start fresh
docker compose up -d

# Wait for database to be ready
sleep 20

# Run migrations
docker compose exec app php artisan migrate --force

# Seed database
docker compose exec app php artisan db:seed --force
```

### 3. **Permission Denied Errors**

**Symptoms:**
```
file_put_contents(/var/www/html/storage/...): Permission denied
```

**Solution:**
```bash
# On host machine
chmod -R 777 storage bootstrap/cache

# Inside container (automatic on startup, but can run manually)
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
```

### 4. **Faker Locale Issues**

**Symptoms:**
```
userName failed with the selected locale. Try a different locale or activate the "intl" PHP extension.
```

**Solution:**
The Dockerfile now includes the `intl` extension. If you encounter this:

```bash
# Rebuild the image
docker compose build app

# Restart containers
docker compose up -d
```

## Deployment Checklist

### Pre-Deployment
- [ ] Docker and Docker Compose installed
- [ ] Port 8182 available
- [ ] Sufficient disk space (at least 5GB recommended)

### During Deployment
- [ ] Run `./deploy.sh`
- [ ] Answer 'y' to deployment confirmation
- [ ] Wait for database health check (may take 30-60 seconds)
- [ ] Answer 'y' or 'n' to database seeding

### Post-Deployment Verification
```bash
# Check containers are running
docker compose ps

# Check application response
curl -I http://localhost:8182

# View logs
docker compose logs -f app

# Test login
# Visit http://YOUR_SERVER_IP:8182
# Login with: superadmin@bogura.gov.bd / password123
```

## Environment Configuration

### For Production Servers

Edit `docker-compose.yml` before deploying:

```yaml
environment:
  - APP_ENV=production        # Change to production
  - APP_DEBUG=false          # Set to false for production
  - APP_URL=http://your-domain.com
```

### For Development/Testing

Keep the default settings:
```yaml
environment:
  - APP_ENV=development
  - APP_DEBUG=true
  - APP_URL=http://localhost:8182
```

## User Permissions (Root vs Non-Root)

**You DO NOT need root user** to run the application. The deploy script works with:
- Regular user with Docker permissions
- User added to `docker` group

```bash
# Add your user to docker group (one time)
sudo usermod -aG docker $USER

# Log out and log back in for changes to take effect
```

**Inside the container**, the application runs as `www-data` user (Apache's default), which is correct and secure.

## Useful Commands

### View Logs
```bash
# Application logs
docker compose logs -f app

# Database logs
docker compose logs -f db

# Laravel logs
docker compose exec app tail -f storage/logs/laravel.log

# Apache error logs
docker compose exec app tail -f /var/log/apache2/error.log
```

### Database Operations
```bash
# Run migrations
docker compose exec app php artisan migrate

# Seed database
docker compose exec app php artisan db:seed

# Check migration status
docker compose exec app php artisan migrate:status

# Access MySQL directly
docker compose exec db mysql -u dc_user -pdc_password_2024 dc_relief_manager
```

### Cache Management
```bash
# Clear all caches
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear

# Cache for production
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

### Container Management
```bash
# Restart application
docker compose restart

# Stop application
docker compose down

# Stop and remove volumes (fresh start)
docker compose down -v

# Rebuild image
docker compose build app

# View container status
docker compose ps

# Access container shell
docker compose exec app bash
```

## Performance Optimization

### For Production

1. **Enable OPcache** (already enabled in Dockerfile)
2. **Cache configuration**:
   ```bash
   docker compose exec app php artisan config:cache
   docker compose exec app php artisan route:cache
   docker compose exec app php artisan view:cache
   ```

3. **Set proper environment**:
   - `APP_ENV=production`
   - `APP_DEBUG=false`

### For Development

Keep caches disabled for faster development:
```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
```

## Security Recommendations

1. **Change default passwords** in production
2. **Use HTTPS** with a reverse proxy (nginx/traefik)
3. **Set `APP_DEBUG=false`** in production
4. **Regular backups** of the database volume
5. **Update regularly**: `git pull && docker compose build && docker compose up -d`

## Backup and Restore

### Backup Database
```bash
# Backup to SQL file
docker compose exec -T db mysqldump -u dc_user -pdc_password_2024 dc_relief_manager > backup.sql

# Or backup with date
docker compose exec -T db mysqldump -u dc_user -pdc_password_2024 dc_relief_manager > backup_$(date +%Y%m%d).sql
```

### Restore Database
```bash
# Restore from backup
docker compose exec -T db mysql -u dc_user -pdc_password_2024 dc_relief_manager < backup.sql
```

### Backup Uploaded Files
```bash
# Backup storage directory
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/
```

## Monitoring

### Check Application Health
```bash
# HTTP status
curl -I http://localhost:8182

# Check if database is accessible
docker compose exec app php artisan migrate:status

# Check disk usage
docker compose exec app df -h

# Check memory usage
docker stats --no-stream
```

## Getting Help

If you encounter issues:

1. Run troubleshooting script: `./troubleshoot.sh`
2. Check logs: `docker compose logs -f app`
3. Try fresh deployment: `docker compose down -v && ./deploy.sh`
4. Check this guide for common issues

## Login Credentials (After Seeding)

| Role | Email | Password |
|------|-------|----------|
| Super Admin | superadmin@bogura.gov.bd | password123 |
| District Admin | dc@bogura.gov.bd | password123 |
| Data Entry | dataentry1@bogura.gov.bd | password123 |
| Viewer | viewer1@bogura.gov.bd | password123 |

**Remember to change these passwords in production!**

