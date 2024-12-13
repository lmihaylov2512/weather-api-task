#!/bin/sh

# install Composer dependencies
echo "Installing composer packages..."
composer install

# run Laravel commands
php artisan key:generate --force
php artisan migrate --force
php artisan cache:clear

# start php-fpm
exec php-fpm
