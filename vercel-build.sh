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

# Create the dist directory
mkdir -p dist

# Copy the public directory to dist
cp -r public/* dist/

# Ensure the api directory exists
mkdir -p api

# Create a symbolic link for storage
php artisan storage:link

echo "Build completed successfully!"