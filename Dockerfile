# Use PHP 8.3 with Apache
FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libgd-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf && \
    echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Copy application code
COPY . .

# Create necessary directories that might be missing
RUN mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

# Install PHP dependencies with verbose output for debugging
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction --prefer-dist -vvv

# Install Node.js 20 and npm (required by vite 7)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install npm dependencies (including dev) and build assets
RUN npm ci && npm run build

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create .env file if it doesn't exist (prefer env.example.dist if present)
RUN if [ ! -f .env ]; then cp env.example.dist .env 2>/dev/null || cp .env.example .env 2>/dev/null || echo "APP_NAME=DC Relief Manager\n\
APP_ENV=production\n\
APP_KEY=\n\
APP_DEBUG=false\n\
APP_URL=http://localhost\n\
\n\
LOG_CHANNEL=stack\n\
LOG_DEPRECATIONS_CHANNEL=null\n\
LOG_LEVEL=debug\n\
\n\
DB_CONNECTION=mysql\n\
DB_HOST=db\n\
DB_PORT=3306\n\
DB_DATABASE=dc_relief_manager\n\
DB_USERNAME=root\n\
DB_PASSWORD=\n\
\n\
BROADCAST_DRIVER=log\n\
CACHE_DRIVER=file\n\
FILESYSTEM_DISK=local\n\
QUEUE_CONNECTION=sync\n\
SESSION_DRIVER=file\n\
SESSION_LIFETIME=120\n\
\n\
MEMCACHED_HOST=127.0.0.1\n\
\n\
REDIS_HOST=127.0.0.1\n\
REDIS_PASSWORD=null\n\
REDIS_PORT=6379\n\
\n\
MAIL_MAILER=smtp\n\
MAIL_HOST=mailpit\n\
MAIL_PORT=1025\n\
MAIL_USERNAME=null\n\
MAIL_PASSWORD=null\n\
MAIL_ENCRYPTION=null\n\
MAIL_FROM_ADDRESS=\"hello@example.com\"\n\
MAIL_FROM_NAME=\"\${APP_NAME}\"\n\
\n\
AWS_ACCESS_KEY_ID=\n\
AWS_SECRET_ACCESS_KEY=\n\
AWS_DEFAULT_REGION=us-east-1\n\
AWS_BUCKET=\n\
AWS_USE_PATH_STYLE_ENDPOINT=false\n\
\n\
PUSHER_APP_ID=\n\
PUSHER_APP_KEY=\n\
PUSHER_APP_SECRET=\n\
PUSHER_HOST=\n\
PUSHER_PORT=443\n\
PUSHER_SCHEME=https\n\
PUSHER_APP_CLUSTER=mt1\n\
\n\
VITE_PUSHER_APP_KEY=\"\${PUSHER_APP_KEY}\"\n\
VITE_PUSHER_HOST=\"\${PUSHER_HOST}\"\n\
VITE_PUSHER_PORT=\"\${PUSHER_PORT}\"\n\
VITE_PUSHER_SCHEME=\"\${PUSHER_SCHEME}\"\n\
VITE_PUSHER_APP_CLUSTER=\"\${PUSHER_APP_CLUSTER}\"" > .env; fi

# Expose port 8182
EXPOSE 8182

# Configure Apache to listen on port 8182
RUN echo "Listen 8182" >> /etc/apache2/ports.conf \
    && sed -i 's/:80/:8182/g' /etc/apache2/sites-available/000-default.conf

# Create startup script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
cd /var/www/html\n\
\n\
# Ensure no stale discovery cache\n\
rm -f bootstrap/cache/packages.php bootstrap/cache/services.php || true\n\
\n\
# Re-discover packages\n\
php artisan package:discover || true\n\
\n\
# Generate application key if not set\n\
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Run database migrations (ignore if DB not ready)\n\
php artisan migrate --force || true\n\
\
\
# Clear and cache config\n\
php artisan config:clear || true\n\
php artisan config:cache || true\n\
\
\
# Clear and cache routes\n\
php artisan route:clear || true\n\
php artisan route:cache || true\n\
\
\
# Clear and cache views\n\
php artisan view:clear || true\n\
php artisan view:cache || true\n\
\n\
# Start Apache\n\
exec apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
