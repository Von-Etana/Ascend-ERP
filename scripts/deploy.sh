#!/bin/bash
set -e

echo "🚀 Deploying Ascend AI to Production (Abuja HQ)..."

# 1. Maintenance Mode
php artisan down || true

# 2. Update Code & Dependencies
git pull origin main
composer install --no-dev --optimize-autoloader

# 3. Database Migrations & Seeders
php artisan migrate --force
php artisan db:seed --class=AscendWorkspaceSeeder --force
php artisan db:seed --class=EnterpriseModuleSeeder --force

# 4. Cache Optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 5. Restart Background Workers
php artisan queue:restart

# 6. Exit Maintenance Mode
php artisan up

echo "✅ Ascend AI Production Deployment Successful!"
