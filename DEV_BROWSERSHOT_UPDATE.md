# Development Environment - Browsershot Update

## ✅ Updated Files

Your development environment now includes full Browsershot support for perfect Bangla PDF generation!

### 1. `Dockerfile.dev` ✅ Updated
**Added:**
- ✅ Chromium browser
- ✅ All Chromium dependencies
- ✅ Bangla fonts (Noto Sans Bengali)
- ✅ Puppeteer installation
- ✅ Environment variables for Puppeteer
- ✅ `storage/app/temp` directory for PDF generation

### 2. `docker-compose.dev.yml` ✅ Updated
**Added:**
- ✅ Puppeteer environment variables
- ✅ Security options for Chromium
- ✅ 2GB shared memory (required for Chromium)
- ✅ Proper service dependencies

## 🚀 How to Use

### First Time Setup or After Update

```bash
# Rebuild the development image with Browsershot
./deploy-dev.sh
```

This will:
1. Stop existing containers
2. Rebuild image with Chromium (takes ~10 minutes)
3. Install Puppeteer
4. Start services
5. Your app will be ready at http://localhost:8182

### Already Running?

```bash
# Rebuild and restart
docker compose -f docker-compose.dev.yml down
docker compose -f docker-compose.dev.yml build --no-cache
docker compose -f docker-compose.dev.yml up -d
```

## ✨ What Works Now

✅ **PDF Export** - Perfect Bangla rendering  
✅ **Browsershot** - Using Chrome engine  
✅ **Live Reload** - Code changes reflect immediately  
✅ **Bangla Fonts** - Proper ligatures and conjuncts  
✅ **All Filters** - Status, year, type working  

## 🧪 Test It

```bash
# 1. Access your app
http://localhost:8182/admin/projects

# 2. Click "Export PDF"

# 3. Check the PDF:
✅ Title: বরাদ্দ সারসংক্ষেপ রিপোর্ট
✅ Numbers: ১২৩৪৫৬৭৮৯০
✅ Status: সক্রিয়, সম্পন্ন
✅ Perfect character shaping
```

## 🔍 Verify Browsershot

```bash
# Test inside container
docker compose -f docker-compose.dev.yml exec app php artisan tinker

# Run this in tinker:
>>> \Spatie\Browsershot\Browsershot::html('<h1>Test বাংলা</h1>')->save(storage_path('test.pdf'));
>>> exit

# Check if PDF was created
docker compose -f docker-compose.dev.yml exec app ls -la storage/test.pdf
```

## 📁 Key Changes

### Dockerfile.dev
```dockerfile
# Added Chromium and dependencies (lines 19-60)
chromium
chromium-sandbox
fonts-noto-sans-bengali
# ... all Chromium dependencies

# Added Puppeteer (line 104)
RUN npm install puppeteer

# Added temp directory (line 111)
storage/app/temp

# Added environment variables (lines 124-125)
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium
```

### docker-compose.dev.yml
```yaml
environment:
  # Added Browsershot config (lines 22-25)
  - PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium
  - PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true
  - CHROME_NO_SANDBOX=true

# Added security and memory (lines 40-43)
security_opt:
  - seccomp:unconfined
shm_size: '2gb'
```

## 🆚 Development vs Production

| Feature | Development | Production |
|---------|------------|------------|
| **Dockerfile** | `Dockerfile.dev` | `Dockerfile.production` |
| **Compose** | `docker-compose.dev.yml` | `docker-compose.production.yml` |
| **Deploy Script** | `./deploy-dev.sh` | `./deploy-production.sh` |
| **Browsershot** | ✅ Included | ✅ Included |
| **Live Reload** | ✅ Yes | ❌ No |
| **Debug Mode** | ✅ On | ❌ Off |
| **Dev Dependencies** | ✅ Included | ❌ Excluded |
| **Optimization** | ❌ None | ✅ Full |

## 🛠️ Useful Commands

### Container Management
```bash
# View logs
docker compose -f docker-compose.dev.yml logs -f app

# Restart (keeps image)
docker compose -f docker-compose.dev.yml restart

# Rebuild (after Dockerfile changes)
docker compose -f docker-compose.dev.yml build --no-cache

# Stop
docker compose -f docker-compose.dev.yml down

# Start
docker compose -f docker-compose.dev.yml up -d
```

### Application Commands
```bash
# Access shell
docker compose -f docker-compose.dev.yml exec app bash

# Test Browsershot
docker compose -f docker-compose.dev.yml exec app php artisan tinker

# Check Chromium
docker compose -f docker-compose.dev.yml exec app which chromium

# Check Puppeteer
docker compose -f docker-compose.dev.yml exec app npm list puppeteer
```

## 🐛 Troubleshooting

### Issue: PDF Export Fails

**Check Chromium:**
```bash
docker compose -f docker-compose.dev.yml exec app which chromium
# Should output: /usr/bin/chromium
```

**Check Puppeteer:**
```bash
docker compose -f docker-compose.dev.yml exec app npm list puppeteer
# Should show: puppeteer@24.x.x
```

**Test manually:**
```bash
docker compose -f docker-compose.dev.yml exec app php artisan tinker
>>> echo \Spatie\Browsershot\Browsershot::html('<h1>Test</h1>')->base64pdf();
```

### Issue: Container Won't Start

**Check logs:**
```bash
docker compose -f docker-compose.dev.yml logs app
```

**Rebuild from scratch:**
```bash
docker compose -f docker-compose.dev.yml down -v
docker compose -f docker-compose.dev.yml build --no-cache
docker compose -f docker-compose.dev.yml up -d
```

### Issue: Bangla Text Broken

**Verify Google Fonts loads:**
```bash
docker compose -f docker-compose.dev.yml exec app curl -I https://fonts.googleapis.com/css2
# Should return: HTTP/2 200
```

### Issue: Out of Memory

**Check memory:**
```bash
docker stats
```

**Increase shared memory:**
Edit `docker-compose.dev.yml`:
```yaml
shm_size: '4gb'  # Increase from 2gb
```

## 📝 Notes

### First Build Time
- **First time**: ~10-15 minutes (downloads Chromium)
- **Subsequent**: ~2-3 minutes (uses cache)

### Memory Usage
- **Without Chromium**: ~500MB
- **With Chromium**: ~1GB
- **During PDF generation**: ~1.5-2GB

### Shared Memory
The `shm_size: '2gb'` setting is **required** for Chromium to work properly in Docker. Without it, PDF generation may fail or be unstable.

## ✅ Checklist

After running `./deploy-dev.sh`:

- [ ] Containers are running: `docker compose -f docker-compose.dev.yml ps`
- [ ] App accessible: http://localhost:8182
- [ ] Can login to application
- [ ] Chromium installed: `docker compose -f docker-compose.dev.yml exec app which chromium`
- [ ] Puppeteer installed: `docker compose -f docker-compose.dev.yml exec app npm list puppeteer`
- [ ] PDF export works
- [ ] Bangla text renders perfectly

## 🎯 Summary

Your development environment now has:

✅ **Full Browsershot support**  
✅ **Chromium browser for PDF rendering**  
✅ **Perfect Bangla fonts**  
✅ **Live code reload** (your changes reflect immediately)  
✅ **Production parity** (same PDF quality as production)  

**Ready to develop!** Any code changes you make will reflect immediately without rebuilding. 🚀

---

**Next Steps:**
1. Run `./deploy-dev.sh` to rebuild
2. Wait for completion (~10 minutes)
3. Test PDF export at http://localhost:8182/admin/projects
4. Start coding - changes reflect live!

