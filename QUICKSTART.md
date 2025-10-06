# DC Relief Manager - Quick Start Guide

Get the DC Relief Manager up and running in minutes!

## 🚀 Quick Deployment

### Prerequisites
- Docker installed on your server
- Port 8182 available

### Deploy in 3 Steps

```bash
# 1. Make deploy script executable
chmod +x deploy.sh

# 2. Run deployment
./deploy.sh

# 3. Access the application
# http://YOUR_SERVER_IP:8182
```

That's it! The application will be running on port 8182.

## 📋 What Happens During Deployment

The `deploy.sh` script will:
1. ✅ Check for Docker and Docker Compose
2. ✅ Create `.env` file if missing
3. ✅ Build Docker images
4. ✅ Start MySQL and Application containers  
5. ✅ Run database migrations
6. ✅ Optionally seed the database
7. ✅ Optimize the application

## 🔧 First Time Setup

### 1. Configure Environment (Optional)

Before running `deploy.sh`, you can customize settings:

```bash
# Copy environment file
cp env.example.dist .env

# Edit configuration
nano .env

# Update these for production:
APP_URL=http://YOUR_SERVER_IP:8182
DB_PASSWORD=your_secure_password
```

### 2. Deploy

```bash
./deploy.sh
```

### 3. Create Admin User

```bash
# Access the application container
docker compose exec app php artisan tinker

# Create an admin user
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123')
]);
```

## 🌐 Accessing Your Application

After deployment:
- **Application**: `http://YOUR_SERVER_IP:8182`
- **Database**: Available on port `3306`

## 🛠️ Common Commands

```bash
# View application logs
docker compose logs -f app

# Restart application
docker compose restart

# Stop application
docker compose down

# Access application shell
docker compose exec app bash

# Run artisan commands
docker compose exec app php artisan migrate
docker compose exec app php artisan cache:clear
```

## 🔥 Firewall Setup (Ubuntu/Debian)

```bash
# Allow application port
sudo ufw allow 8182/tcp

# Enable firewall
sudo ufw enable
```

## 📦 Database Management

```bash
# Access MySQL
docker compose exec db mysql -u dc_user -p dc_relief_manager
# Password: dc_password_2024

# Backup database
docker compose exec db mysqldump -u dc_user -pdc_password_2024 dc_relief_manager > backup.sql

# Restore database
docker compose exec -T db mysql -u dc_user -pdc_password_2024 dc_relief_manager < backup.sql
```

## 🔒 Production Checklist

Before going live:

- [ ] Change database passwords in `docker-compose.yml` and `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Update `APP_URL` with your actual server IP/domain
- [ ] Generate secure `APP_KEY` (done automatically)
- [ ] Configure firewall to allow only necessary ports
- [ ] Set up regular database backups
- [ ] Consider SSL/HTTPS (see DEPLOYMENT.md)

## ❓ Troubleshooting

### Application not loading?
```bash
# Check if containers are running
docker compose ps

# View logs
docker compose logs app

# Restart
docker compose restart
```

### Can't access port 8182?
```bash
# Check if port is in use
sudo lsof -i :8182

# Check firewall
sudo ufw status

# Allow port in firewall
sudo ufw allow 8182/tcp
```

### Database connection error?
```bash
# Check database is running
docker compose logs db

# Verify credentials match in .env and docker-compose.yml
```

## 📚 Need More Help?

See detailed documentation in `DEPLOYMENT.md` for:
- SSL/HTTPS setup
- Nginx reverse proxy
- Advanced configuration
- Security best practices
- Backup strategies

---

**Default Credentials** (change immediately!):
- Database User: `dc_user`
- Database Password: `dc_password_2024`
- Database Root Password: `root_password_2024`

