#!/bin/bash

# Exit on error
set -e

echo "Starting Vercel build process..."

# Create the api directory if it doesn't exist
mkdir -p api

# Create a simple index.php file in the api directory
cat > api/index.php << 'EOL'
<?php
// Forward Vercel requests to normal index.php
require __DIR__ . '/../public/index.php';
EOL

# Copy the production environment file
echo "Setting up environment..."
cp .env.production .env

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build frontend assets
echo "Building frontend assets..."
npm ci
npm run build

# Create storage link
echo "Creating storage link..."
php artisan storage:link || true

echo "Build completed successfully!"