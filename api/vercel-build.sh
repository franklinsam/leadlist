#!/bin/bash

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --show
    echo "Please set the generated key in your Vercel environment variables as APP_KEY"
fi

# Clear and cache routes, config, etc.
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink if needed
php artisan storage:link || true

echo "Build completed successfully!" 