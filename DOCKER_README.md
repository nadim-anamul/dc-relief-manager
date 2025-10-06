# 🐳 Docker Deployment - DC Relief Manager

## 📋 Overview

This project now includes a complete Docker-based deployment system for easy server deployment. The application runs on **port 8182** and includes all necessary services.

## 📁 Files Created

### Core Docker Files
- **`Dockerfile`** - Main Docker image definition (PHP 8.3 + Apache + Node.js)
- **`docker-compose.yml`** - Service orchestration (App + MySQL)
- **`docker-entrypoint.sh`** - Container startup script
- **`.dockerignore`** - Files to exclude from Docker build

### Deployment Scripts
- **`deploy.sh`** - Production deployment script
- **`test-deployment.sh`** - Local testing script

### Documentation
- **`DEPLOYMENT.md`** - Complete deployment guide with SSL, firewall, troubleshooting
- **`QUICKSTART.md`** - Quick start guide for rapid deployment
- **`DOCKER_README.md`** - This file

### Configuration
- **`env.example.dist`** - Updated with Docker database credentials

## 🚀 Quick Deploy

### For Server Deployment

```bash
# 1. Upload project to server
scp -r dc-relief-manager user@server:/path/to/

# 2. SSH into server
ssh user@server
cd /path/to/dc-relief-manager

# 3. Make scripts executable
chmod +x deploy.sh

# 4. Deploy
./deploy.sh

# 5. Access application
# http://YOUR_SERVER_IP:8182
```

### For Local Testing

```bash
# Test deployment locally
chmod +x test-deployment.sh
./test-deployment.sh

# Access at http://localhost:8182
```

## 🔧 What's Included

### Application Container
- **Base**: PHP 8.3 with Apache
- **PHP Extensions**: GD, PDO MySQL, MBString, ZIP, BCMath, etc.
- **Node.js**: Version 20 (for Vite)
- **Composer**: Latest version
- **Port**: 8182 (configured in Apache)

### Database Container
- **Image**: MySQL 8.0
- **Database**: dc_relief_manager
- **User**: dc_user
- **Password**: dc_password_2024 (change in production!)
- **Port**: 3306 (exposed for external access)

### Features
✅ Automatic migrations on startup  
✅ Application key generation  
✅ Storage linking  
✅ Configuration caching  
✅ Route caching  
✅ View caching  
✅ Health checks for database  
✅ Persistent storage volumes  

## 🌐 Access Points

After deployment:
- **Application**: `http://YOUR_SERVER_IP:8182`
- **Database**: `YOUR_SERVER_IP:3306`

## 📝 Important Notes

### Security Checklist
Before production deployment:

1. **Change Database Passwords**
   - Edit `docker-compose.yml`
   - Update `MYSQL_PASSWORD` and `MYSQL_ROOT_PASSWORD`
   - Update `.env` to match

2. **Update Environment**
   - Set `APP_DEBUG=false` in `.env`
   - Update `APP_URL` with your actual server IP/domain

3. **Firewall Configuration**
   ```bash
   sudo ufw allow 8182/tcp
   sudo ufw enable
   ```

4. **SSL Setup** (Production)
   - See `DEPLOYMENT.md` for Nginx + Let's Encrypt setup

### Default Credentials

**Database** (⚠️ CHANGE THESE!):
- User: `dc_user`
- Password: `dc_password_2024`
- Root Password: `root_password_2024`

**Application** (from seeders):
- Super Admin: `superadmin@dcrelief.com` / `password123`
- District Admin: `districtadmin@dcrelief.com` / `password123`
- Data Entry: `dataentry@dcrelief.com` / `password123`
- Viewer: `viewer@dcrelief.com` / `password123`

## 🛠️ Common Commands

```bash
# View logs
docker compose logs -f app
docker compose logs -f db

# Restart services
docker compose restart

# Stop services
docker compose down

# Rebuild and restart
docker compose down
docker compose up -d --build

# Access application shell
docker compose exec app bash

# Run artisan commands
docker compose exec app php artisan migrate
docker compose exec app php artisan cache:clear
docker compose exec app php artisan db:seed

# Access MySQL
docker compose exec db mysql -u dc_user -p dc_relief_manager

# Backup database
docker compose exec db mysqldump -u dc_user -pdc_password_2024 dc_relief_manager > backup.sql

# Restore database
docker compose exec -T db mysql -u dc_user -pdc_password_2024 dc_relief_manager < backup.sql
```

## 🔍 Troubleshooting

### Application not accessible?
```bash
# Check containers are running
docker compose ps

# Check logs
docker compose logs app

# Restart
docker compose restart app
```

### Database connection errors?
```bash
# Check database is running
docker compose logs db

# Verify credentials in .env match docker-compose.yml
cat .env | grep DB_
```

### Permission errors?
```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 755 storage bootstrap/cache
```

### Port already in use?
```bash
# Check what's using port 8182
sudo lsof -i :8182

# Kill the process or change port in docker-compose.yml
```

## 📚 Documentation Structure

1. **QUICKSTART.md** → Start here for rapid deployment
2. **DEPLOYMENT.md** → Complete guide with advanced topics
3. **DOCKER_README.md** → This file - Docker overview
4. **README.md** → Application documentation

## 🔄 Update Process

```bash
# Pull latest changes
git pull origin main

# Rebuild and restart
docker compose down
docker compose up -d --build

# Run migrations
docker compose exec app php artisan migrate --force

# Clear cache
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

## 🎯 Architecture

```
┌─────────────────────────────────────┐
│          Internet/Network           │
└─────────────┬───────────────────────┘
              │
              ↓ Port 8182
┌─────────────────────────────────────┐
│    DC Relief Manager (Apache)       │
│                                     │
│  - PHP 8.3                          │
│  - Laravel Application              │
│  - Apache on port 8182              │
└─────────────┬───────────────────────┘
              │
              ↓ Internal Network
┌─────────────────────────────────────┐
│        MySQL Database               │
│                                     │
│  - MySQL 8.0                        │
│  - Port 3306                        │
│  - Persistent Volume                │
└─────────────────────────────────────┘
```

## 💡 Tips

1. **Development**: Use `test-deployment.sh` for local testing
2. **Production**: Use `deploy.sh` for server deployment
3. **Monitoring**: Check logs regularly with `docker compose logs -f`
4. **Backups**: Set up automated database backups (see DEPLOYMENT.md)
5. **Updates**: Pull changes and rebuild with `--build` flag
6. **Security**: Always change default passwords before production use

## 📞 Support

- **Issues**: Check `DEPLOYMENT.md` troubleshooting section
- **Logs**: `docker compose logs -f app`
- **Laravel Logs**: `docker compose exec app tail -f storage/logs/laravel.log`

---

**Built with ❤️ for easy deployment**

