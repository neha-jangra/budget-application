#!/bin/bash

# Navigate to the project directory
cd /Library/WebServer/Documents/laravel-projects/Budget-app

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services if needed
sudo service php8.2-fpm restart
