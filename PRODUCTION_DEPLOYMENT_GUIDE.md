# Production Deployment Guide with Browsershot

## 🚀 Quick Start

Deploy your application with perfect Bangla PDF support in 3 steps:

```bash
# 1. Make deployment script executable (if not already)
chmod +x deploy-production.sh

# 2. Run deployment
./deploy-production.sh

# 3. Access your application
# http://139.162.3.19:8182
```

## 📋 What's Included

### ✅ Production Features
- **Browsershot/Puppeteer**: Perfect Bangla PDF rendering
- **Chromium**: Headless browser for PDF generation
- **Bangla Fonts**: Noto Sans Bengali pre-installed
- **Optimized**: Production-ready with caching
- **Secure**: No dev dependencies, optimized security
- **Auto-migration**: Database migrations run automatically

### ✅ PDF Export Features
- Perfect Bangla text rendering with ligatures
- Complex script shaping (conjuncts, vowel marks)
- Google Fonts support
- Professional typography
- All filters working (status, year, type)

## 📁 New Files Created

1. **`Dockerfile.production`** - Production Docker image with Browsershot
2. **`docker-compose.production.yml`** - Production docker-compose config
3. **`docker-entrypoint-production.sh`** - Production startup script
4. **`deploy-production.sh`** - One-command deployment script

## 🔧 System Requirements

### Server Requirements:
- **Docker**: 20.10 or higher
- **Docker Compose**: 2.0 or higher
- **RAM**: Minimum 2GB (4GB recommended for Chromium)
- **Disk**: Minimum 5GB free space
- **OS**: Ubuntu 20.04+, Debian 11+, or similar

### Ports Used:
- **8182**: Application (HTTP)
- **33011**: MySQL (external access)

## 📖 Deployment Steps

### Step 1: Prepare Your Server

```bash
# Update system
sudo apt-get update && sudo apt-get upgrade -y

# Install Docker (if not installed)
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose (if not installed)
sudo apt-get install docker-compose-plugin -y

# Verify installations
docker --version
docker compose version
```

### Step 2: Upload Your Code

```bash
# Option A: Using Git (recommended)
git clone your-repo-url dc-relief-manager
cd dc-relief-manager

# Option B: Using SCP
scp -r /local/path/dc-relief-manager user@server:/path/to/
```

### Step 3: Configure Environment

```bash
# Copy .env file
cp env.example.dist .env

# Edit .env for production
nano .env
```

**Important .env settings:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-server-ip:8182

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=dc_relief_manager
DB_USERNAME=dc_user
DB_PASSWORD=your_secure_password_here  # Change this!

# Browsershot settings (these are set in docker-compose)
PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium
```

### Step 4: Deploy

```bash
# Make script executable
chmod +x deploy-production.sh

# Run deployment
./deploy-production.sh
```

The script will:
1. ✅ Stop existing containers
2. ✅ Create necessary directories
3. ✅ Build production Docker image (5-10 minutes)
4. ✅ Install Chromium and dependencies
5. ✅ Install Puppeteer
6. ✅ Start services
7. ✅ Wait for database
8. ✅ Test Browsershot
9. ✅ Run migrations
10. ✅ Optimize for production

### Step 5: Verify Deployment

```bash
# Check if containers are running
docker compose -f docker-compose.production.yml ps

# Check application logs
docker compose -f docker-compose.production.yml logs -f app

# Test Browsershot
docker compose -f docker-compose.production.yml exec app php artisan tinker --execute="
echo \Spatie\Browsershot\Browsershot::html('<h1>Test</h1>')->base64pdf();
"
```

## 🧪 Testing PDF Export

1. **Visit**: `http://139.162.3.19:8182/admin/projects`
2. **Login** with your credentials
3. **Click**: "Export PDF" button
4. **Check**: Bangla text should render perfectly

### Expected Results:
- ✅ Title: "বরাদ্দ সারসংক্ষেপ রিপোর্ট"
- ✅ Numbers: ১২৩৪৫৬৭৮৯০
- ✅ Status: সক্রিয়, সম্পন্ন
- ✅ All text properly shaped with ligatures

## 🔍 Key Differences from Development

| Feature | Development | Production |
|---------|------------|------------|
| Image | `Dockerfile.dev` | `Dockerfile.production` |
| Compose | `docker-compose.dev.yml` | `docker-compose.production.yml` |
| Debug Mode | ✅ Enabled | ❌ Disabled |
| Dev Dependencies | ✅ Included | ❌ Excluded |
| Caching | ❌ Disabled | ✅ Enabled |
| Optimization | ❌ None | ✅ Full |
| Live Reload | ✅ Yes | ❌ No |
| Browsershot | ✅ Works | ✅ Works |
| Security | Standard | Hardened |

## 🛠️ Useful Commands

### Container Management
```bash
# View logs
docker compose -f docker-compose.production.yml logs -f app

# Restart application
docker compose -f docker-compose.production.yml restart

# Stop application
docker compose -f docker-compose.production.yml down

# Start application
docker compose -f docker-compose.production.yml up -d

# Rebuild image
docker compose -f docker-compose.production.yml build --no-cache
```

### Application Commands
```bash
# Access container shell
docker compose -f docker-compose.production.yml exec app bash

# Run migrations
docker compose -f docker-compose.production.yml exec app php artisan migrate --force

# Clear cache
docker compose -f docker-compose.production.yml exec app php artisan optimize:clear

# Optimize
docker compose -f docker-compose.production.yml exec app php artisan optimize

# Test PDF
docker compose -f docker-compose.production.yml exec app php artisan tinker
```

### Database Commands
```bash
# Access MySQL
docker compose -f docker-compose.production.yml exec db mysql -u dc_user -p

# Backup database
docker compose -f docker-compose.production.yml exec db mysqldump -u dc_user -p dc_relief_manager > backup.sql

# Restore database
docker compose -f docker-compose.production.yml exec -T db mysql -u dc_user -p dc_relief_manager < backup.sql
```

## 🔧 Troubleshooting

### Issue: PDF Generation Fails

**Symptoms**: Error when clicking "Export PDF"

**Solutions**:
```bash
# 1. Check if Chromium is installed
docker compose -f docker-compose.production.yml exec app which chromium

# 2. Check Puppeteer
docker compose -f docker-compose.production.yml exec app npm list puppeteer

# 3. Test Browsershot
docker compose -f docker-compose.production.yml exec app php artisan tinker --execute="
echo \Spatie\Browsershot\Browsershot::html('<h1>Test</h1>')->base64pdf();
"

# 4. Check logs
docker compose -f docker-compose.production.yml logs app | grep -i chromium
docker compose -f docker-compose.production.yml logs app | grep -i puppeteer
```

### Issue: Bangla Text Broken

**Symptoms**: Bangla characters disconnected in PDF

**Solutions**:
```bash
# 1. Verify Google Fonts loads
docker compose -f docker-compose.production.yml exec app curl -I https://fonts.googleapis.com/css2

# 2. Check internet connectivity from container
docker compose -f docker-compose.production.yml exec app ping -c 3 8.8.8.8

# 3. If no internet, fonts won't load - need to embed fonts locally
# (See advanced configuration below)
```

### Issue: Container Out of Memory

**Symptoms**: Container crashes, PDF generation fails randomly

**Solutions**:
```bash
# 1. Check memory usage
docker stats

# 2. Increase shared memory (already set to 2GB in docker-compose)
# Edit docker-compose.production.yml:
#   shm_size: '4gb'  # Increase to 4GB

# 3. Restart containers
docker compose -f docker-compose.production.yml restart
```

### Issue: Slow PDF Generation

**Symptoms**: Takes >30 seconds to generate PDF

**Solutions**:
1. **Normal**: First generation takes 5-10s (loads fonts)
2. **Subsequent**: Should be 3-5s
3. **If always slow**: Increase server resources

## 🔐 Security Recommendations

### 1. Change Default Passwords
```bash
# Edit docker-compose.production.yml
# Change:
#   - DB_PASSWORD=dc_password_2024
#   - MYSQL_PASSWORD=dc_password_2024
#   - MYSQL_ROOT_PASSWORD=root_password_2024
```

### 2. Use HTTPS (Recommended)
```bash
# Use reverse proxy (Nginx/Caddy) for SSL
# Or use Let's Encrypt directly
```

### 3. Firewall Rules
```bash
# Allow only necessary ports
sudo ufw allow 8182/tcp
sudo ufw allow 33011/tcp  # Only if external DB access needed
sudo ufw enable
```

### 4. Regular Updates
```bash
# Update Docker images
docker compose -f docker-compose.production.yml pull
docker compose -f docker-compose.production.yml up -d

# Update application
git pull
./deploy-production.sh
```

## 📊 Performance Optimization

### 1. Enable OPcache
Already enabled in production Dockerfile.

### 2. Use Redis for Caching (Optional)
Add to `docker-compose.production.yml`:
```yaml
redis:
  image: redis:alpine
  restart: unless-stopped
  networks:
    - dc_network
```

### 3. Database Optimization
```bash
# Access MySQL
docker compose -f docker-compose.production.yml exec db mysql -u root -p

# Run:
OPTIMIZE TABLE relief_applications;
ANALYZE TABLE projects;
```

## 🔄 Updating Production

### Option 1: Full Redeploy
```bash
git pull  # or upload new code
./deploy-production.sh
```

### Option 2: Quick Update (no rebuild)
```bash
git pull
docker compose -f docker-compose.production.yml exec app composer install --no-dev --optimize-autoloader
docker compose -f docker-compose.production.yml exec app php artisan migrate --force
docker compose -f docker-compose.production.yml exec app php artisan optimize
docker compose -f docker-compose.production.yml restart
```

## 📝 Monitoring

### Check Application Health
```bash
# Application status
curl http://localhost:8182

# Database status
docker compose -f docker-compose.production.yml exec db mysqladmin ping -u dc_user -p

# Container health
docker compose -f docker-compose.production.yml ps

# Resource usage
docker stats
```

### Log Monitoring
```bash
# Real-time logs
docker compose -f docker-compose.production.yml logs -f

# Last 100 lines
docker compose -f docker-compose.production.yml logs --tail=100

# Error logs only
docker compose -f docker-compose.production.yml logs | grep -i error
```

## 🆘 Support

### Common Issues Fixed:
✅ Browsershot working in Docker  
✅ Bangla fonts rendering perfectly  
✅ PDF generation optimized  
✅ Production security hardened  
✅ Automatic migrations  
✅ Cache optimization  

### If You Need Help:
1. Check logs: `docker compose -f docker-compose.production.yml logs -f`
2. Verify containers: `docker compose -f docker-compose.production.yml ps`
3. Test Browsershot: See troubleshooting section
4. Check system resources: `docker stats`

---

## 🎉 Success Checklist

After deployment, verify:

- [ ] Application accessible at http://your-ip:8182
- [ ] Can login successfully
- [ ] Can view projects list
- [ ] "Export PDF" button works
- [ ] PDF downloads successfully
- [ ] Bangla text renders perfectly in PDF
- [ ] Numbers show as ১২৩৪৫৬৭৮৯০
- [ ] Status badges show সক্রিয়, সম্পন্ন
- [ ] No boxes/squares in place of Bangla text

If all checked ✓ - **Production deployment successful!** 🚀

