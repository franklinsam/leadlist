#!/bin/bash

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

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

echo "Build completed successfully!"