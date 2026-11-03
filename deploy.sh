#!/bin/bash
set -euo pipefail

# Resolve PHP/Composer explicitly; this script also runs over non-interactive SSH.
cd "$(dirname "$0")"

echo "Pulling latest backend code..."
git pull --ff-only origin main

echo "Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

echo "Running database migrations..."
php artisan migrate --force

echo "Caching config, routes and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Backend deploy complete."
