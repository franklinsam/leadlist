#!/bin/bash

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

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