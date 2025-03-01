#!/bin/bash

# Install PHP dependencies with optimized autoloader
composer install --no-dev --optimize-autoloader

# Clear any cached configurations
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Regenerate autoload files
composer dump-autoload -o

# Install Node.js dependencies
npm ci

# Build frontend assets
npm run build

# Create dist directory
mkdir -p dist

# Copy necessary files to dist
cp -r public/* dist/
cp -r public/.htaccess dist/ 2>/dev/null || :

# Copy built assets to dist
cp -r public/build dist/ 2>/dev/null || :

# Ensure the api directory exists
mkdir -p api

echo "Build completed successfully!"