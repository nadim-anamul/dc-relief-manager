# DC Relief Manager – Hostinger Deployment Guide

This guide walks you through hosting `DC Relief Manager` on Hostinger (hPanel) shared, premium, or cloud plans. It assumes you already have the project codebase, a purchase of a Hostinger plan, and basic familiarity with SSH and hPanel.

---

## 1. Plan & Prerequisites

**Choose a plan**
- PHP 8.1 or higher support (Premium Shared or better).
- SSH access (enabled in hPanel).
- MySQL database slot available.

**Local requirements**
- PHP 8.1+
- Composer
- Node.js 18+ (build assets locally)
- Git (optional but recommended)

**Resources you'll need handy**
- Subdomain `relief.dcofficeutility.com` pointing to Hostinger.
- Database connection details (host, name, user, password).

---

## 2. Prepare the Application Locally

1. Install PHP dependencies:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
2. Build production assets:
   ```bash
   npm install
   npm run build
   ```
3. Copy the environment template:
   ```bash
   cp .env.example .env
   ```
4. Edit `.env` with production-ready values (keep placeholders for now; we’ll update after creating the Hostinger database).
5. Generate the application key:
   ```bash
   php artisan key:generate
   ```
6. Cache the framework metadata (optional but recommended):
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
7. Clear leftover caches to avoid stale data on deploy:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```
8. Create a deployment archive that excludes local-only directories (`node_modules`, `vendor` if you plan to run Composer on the server, `.git`, `storage/logs`, etc.). Example using `zip`:
   ```bash
   zip -r dc-relief-manager.zip \
     app bootstrap config database public resources routes storage artisan composer.* package.json vite.config.js \
     -x "storage/logs/*" "storage/framework/sessions/*" "storage/framework/views/*" "node_modules/*" "vendor/*" ".git/*"
   ```

> **Tip:** If Hostinger plan has limited resources for Composer, keep `vendor/` in the archive and skip running Composer remotely.

---

## 3. Create Infrastructure in Hostinger

1. **Access hPanel → Databases → MySQL Databases**
   - Create a new database and user.
   - Note the database name, username, password, and server (e.g., `mysql.hostinger.com`).

2. **Set up the domain**
   - hPanel → Domains → Manage → DNS.
   - Point `relief.dcofficeutility.com` to Hostinger’s nameservers or configure an `A` record to your plan’s IP.
   - Wait for propagation (can take up to 24h).

3. **Enable SSH**
   - hPanel → Advanced → SSH Access → Enable.
   - Copy the SSH credentials.

---

## 4. Upload Code & Structure Folders

Hostinger’s document root is typically `public_html`. Laravel’s public assets live in `public/`, so we need to align these.

**Option A – Recommended (Laravel in subfolder, public linked to public_html)**
1. SSH into the server:
   ```bash
   ssh username@your-hostinger-server
   ```
2. Create a project directory above `public_html`:
   ```bash
   mkdir -p ~/domains/relief.dcofficeutility.com/dc-relief-manager
   ```
3. Upload `dc-relief-manager.zip` (via hPanel File Manager, SFTP, or `scp`) into the project directory and unzip:
   ```bash
   cd ~/domains/relief.dcofficeutility.com/dc-relief-manager
   unzip dc-relief-manager.zip
   ```
4. Move the existing `public_html` content (if any) out of the way, then copy Laravel’s `public` contents into `public_html`:
   ```bash
   rm -rf ~/domains/relief.dcofficeutility.com/public_html/*
   cp -R public/* ~/domains/relief.dcofficeutility.com/public_html/
   ```
5. Edit `~/domains/relief.dcofficeutility.com/public_html/index.php` so the framework paths point to the project directory:
   ```php
   require __DIR__.'/../dc-relief-manager/vendor/autoload.php';
   $app = require_once __DIR__.'/../dc-relief-manager/bootstrap/app.php';
   ```

**Option B – Symlink public folder**
- Instead of copying, you can symlink the public directory:
  ```bash
  rm -rf ~/domains/relief.dcofficeutility.com/public_html
  ln -s ~/domains/relief.dcofficeutility.com/dc-relief-manager/public ~/domains/relief.dcofficeutility.com/public_html
  ```
- Only use this if your plan allows symlinks and you have SSH.

---

## 5. Install Dependencies on Hostinger

**If vendor/ was not uploaded:**
```bash
cd ~/domains/relief.dcofficeutility.com/dc-relief-manager
composer install --optimize-autoloader --no-dev
```

> Shared plans have strict resource limits. If Composer fails, upload `vendor/` from your local machine instead.

---

## 6. Configure Environment & Permissions

1. Copy the production `.env`:
   ```bash
   cp .env.example .env
   ```
2. Update `.env` with real credentials:
   - `APP_NAME`, `APP_URL=https://relief.dcofficeutility.com`
   - `DB_CONNECTION=mysql`
   - `DB_HOST`, `DB_PORT`
   - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - Mail settings if using SMTP
   - Cache/queue drivers (Redis is not available on Shared; keep `database` or `sync`)
3. Re-run the artisan commands:
   ```bash
   php artisan key:generate
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
4. Set folder permissions:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```
   If the plan uses different user/group ownership, adjust with `chown` via support or file manager.
5. Create the storage symlink for public uploads:
   ```bash
   php artisan storage:link
   ```

---

## 7. Database Migration & Seed Data

Run the migrations (and seed if desired) from the project root:
```bash
php artisan migrate --force
php artisan db:seed --force
```

> `--force` is required in production to bypass the confirmation prompt.

If the database is large, consider importing a SQL dump using hPanel → Databases → phpMyAdmin instead.

---

## 8. Configure Scheduler & Queue (Optional but Recommended)

### Laravel Scheduler
Create a cron entry in hPanel → Advanced → Cron Jobs:
```
* * * * * cd /home/username/domains/relief.dcofficeutility.com/dc-relief-manager && php artisan schedule:run >> /home/username/laravel-schedule.log 2>&1
```
This runs the scheduler every minute.

### Queue Worker
If the app uses queued jobs, add a cron job:
```
* * * * * cd /home/username/domains/relief.dcofficeutility.com/dc-relief-manager && php artisan queue:work --stop-when-empty >> /home/username/laravel-queue.log 2>&1
```
Hostinger shared hosting does not support long-running daemon processes, so leverage cron to trigger queue workers frequently.

---

## 9. Configure HTTPS

1. hPanel → Websites → Manage → SSL.
2. Issue the free Let’s Encrypt SSL certificate for the domain.
3. Update `.env` with `APP_URL=https://relief.dcofficeutility.com`.
4. Force HTTPS by adding to `app/Providers/AppServiceProvider.php` inside `boot()`:
   ```php
   if (config('app.env') === 'production') {
       \URL::forceScheme('https');
   }
   ```
   Then run `php artisan config:cache`.

---

## 10. Post-Deployment Checks

- Visit `https://relief.dcofficeutility.com` and verify the application loads.
- Test authentication and key workflows.
- Confirm file uploads resolve correctly (storage symlink).
- Check `storage/logs/laravel.log` for runtime errors.
- Confirm cron job logs (`laravel-schedule.log`, `laravel-queue.log`) show successful execution.

---

## Troubleshooting Tips

- **500 Error / White Screen:** Check `storage/logs/laravel.log`, ensure `.env` has correct `APP_KEY`, rerun `php artisan config:cache`.
- **Composer memory errors:** Upload `vendor/` from local or contact Hostinger support to temporarily raise limits.
- **Assets not loading:** Confirm `npm run build` was executed locally and the contents of `public/build` exist. Re-upload if missing.
- **Permission denied:** Ensure `storage` and `bootstrap/cache` have write permissions and the correct ownership.
- **Queue jobs stuck:** Switch `QUEUE_CONNECTION=sync` until cron configuration is stable.

---

## 11. Git Setup for Manual Updates

Set up Git in your project directory so you can pull updates when code changes are made. This is simpler than using hooks and gives you full control over when to deploy.

### Initialize Git on Hostinger
1. SSH into Hostinger and navigate to your project directory:
   ```bash
   ssh username@your-hostinger-server
   cd ~/domains/relief.dcofficeutility.com/dc-relief-manager
   ```

2. Initialize Git (if not already initialized):
   ```bash
   git init
   ```

3. Add your remote repository:
   ```bash
   git remote add origin https://github.com/yourusername/dc-relief-manager.git
   # OR if using SSH:
   git remote add origin git@github.com:yourusername/dc-relief-manager.git
   ```

4. If the directory already has files, add them and make an initial commit:
   ```bash
   git add .
   git commit -m "Initial commit from Hostinger"
   ```

5. Pull from your repository:
   ```bash
   git pull origin main --allow-unrelated-histories
   # OR if your default branch is 'master':
   git pull origin master --allow-unrelated-histories
   ```

### Updating Code (When Changes Are Made)
When you push changes to your repository, pull them on Hostinger:

1. SSH into Hostinger:
   ```bash
   ssh username@your-hostinger-server
   cd ~/domains/relief.dcofficeutility.com/dc-relief-manager
   ```

2. Pull the latest changes:
   ```bash
   git pull origin main
   # OR: git pull origin master
   ```

3. After pulling, run post-update commands:
   ```bash
   # Install/update PHP dependencies if composer.json changed
   composer install --optimize-autoloader --no-dev

   # Run migrations if database changes were made
   php artisan migrate --force

   # Clear and rebuild caches
   php artisan config:clear
   php artisan config:cache
   php artisan route:clear
   php artisan route:cache
   php artisan view:clear
   php artisan view:cache

   # Recreate storage symlink if needed (since exec/symlink may be disabled)
   rm -rf public/storage
   ln -s ~/domains/relief.dcofficeutility.com/dc-relief-manager/storage/app/public ~/domains/relief.dcofficeutility.com/dc-relief-manager/public/storage
   ```

> **Notes**
- If you built assets locally before pushing, you don't need to run `npm install` or `npm run build` on Hostinger.
- If `composer install` fails due to resource limits, upload the `vendor/` directory from your local machine instead.
- Always test changes in a staging environment before pulling to production.
- Consider creating a simple deployment script to automate the post-pull steps.

---

## Useful Commands Reference

```bash
# Clear and recache configuration
php artisan config:clear
php artisan config:cache

# Clear cached views and routes
php artisan view:clear
php artisan route:clear

# Storage link (if broken after deploy)
php artisan storage:link
```

---

## Next Steps

- Automate deployments via Git + GitHub Actions pushing to Hostinger (upload via FTP/SFTP).
- Monitor application health (use Laravel Telescope in a secure environment).
- Schedule regular database backups via hPanel or external backup service.

---

By following this checklist you’ll have `DC Relief Manager` running smoothly on Hostinger’s infrastructure with production best practices in place.

