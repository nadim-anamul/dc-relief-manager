# 🚀 Quick Production Deployment

## One-Line Deploy

```bash
./deploy-production.sh
```

## What It Does

1. ✅ Builds Docker image with Chromium + Puppeteer
2. ✅ Installs Bangla fonts (Noto Sans Bengali)
3. ✅ Configures Browsershot for PDF generation
4. ✅ Starts MySQL database
5. ✅ Runs migrations
6. ✅ Optimizes for production
7. ✅ Tests PDF generation

**Time**: ~10 minutes first time, ~2 minutes updates

## Files You Need

| File | Purpose |
|------|---------|
| `Dockerfile.production` | Docker image with Browsershot |
| `docker-compose.production.yml` | Production services |
| `docker-entrypoint-production.sh` | Startup script |
| `deploy-production.sh` | One-command deployment |

## Before Deployment

```bash
# 1. Update .env file
cp env.example.dist .env
nano .env  # Change passwords!

# 2. Make script executable
chmod +x deploy-production.sh
```

## Deploy

```bash
./deploy-production.sh
```

## After Deployment

**Test it:**
1. Go to: `http://your-ip:8182/admin/projects`
2. Click: "Export PDF"
3. Check: Bangla text perfect? ✅

## Daily Commands

```bash
# View logs
docker compose -f docker-compose.production.yml logs -f app

# Restart
docker compose -f docker-compose.production.yml restart

# Stop
docker compose -f docker-compose.production.yml down

# Start
docker compose -f docker-compose.production.yml up -d
```

## Update Application

```bash
git pull
./deploy-production.sh
```

## Key Features

✅ **Browsershot**: Perfect Bangla PDFs  
✅ **Chromium**: Headless browser included  
✅ **Fonts**: Noto Sans Bengali pre-installed  
✅ **Optimized**: Production caching enabled  
✅ **Secure**: No dev dependencies  
✅ **Auto-migrate**: Database updates automatic  

## Troubleshooting

**PDF not working?**
```bash
# Test Browsershot
docker compose -f docker-compose.production.yml exec app php artisan tinker
>>> \Spatie\Browsershot\Browsershot::html('<h1>Test বাংলা</h1>')->save(storage_path('test.pdf'));
```

**Bangla broken?**
- Check internet (needs Google Fonts)
- Verify Chromium: `docker compose -f docker-compose.production.yml exec app which chromium`

**Out of memory?**
- Increase: Edit `docker-compose.production.yml` → `shm_size: '4gb'`

## Server Requirements

- **Docker**: 20.10+
- **RAM**: 2GB minimum (4GB recommended)
- **Disk**: 5GB free
- **OS**: Ubuntu 20.04+

## Ports

- **8182**: Application
- **33011**: MySQL (external)

## Success Check

✅ Application loads  
✅ Can login  
✅ Projects list shows  
✅ PDF export works  
✅ Bangla renders perfectly  

**All good? You're production-ready!** 🎉

---

**Full docs**: See `PRODUCTION_DEPLOYMENT_GUIDE.md`

