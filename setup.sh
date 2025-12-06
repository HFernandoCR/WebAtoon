#!/bin/bash

# Exit on error
set -e

echo "Starting setup for WebAtoon (MySQL Edition)..."

# 1. Install PHP dependencies
echo "Installing Composer dependencies..."
composer install

# 2. Copy .env file
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    cp .env.example .env
else
    echo ".env file already exists."
fi

# 3. Generate App Key
echo "Generating Application Key..."
php artisan key:generate

# 4. Configure Database
echo "Database Configuration"
read -p "Enter Database Name (default: webatoon): " DB_NAME
DB_NAME=${DB_NAME:-webatoon}
read -p "Enter Database User (default: root): " DB_USER
DB_USER=${DB_USER:-root}
read -s -p "Enter Database Password: " DB_PASS
echo ""

# Update .env with provided credentials
# We use a temporary file to avoid issues with sed on different OS versions
if [ "$(uname)" = "Darwin" ]; then
    sed -i '' "s/^DB_DATABASE=.*$/DB_DATABASE=$DB_NAME/" .env
    sed -i '' "s/^DB_USERNAME=.*$/DB_USERNAME=$DB_USER/" .env
    sed -i '' "s/^DB_PASSWORD=.*$/DB_PASSWORD=$DB_PASS/" .env
else
    sed -i "s/^DB_DATABASE=.*$/DB_DATABASE=$DB_NAME/" .env
    sed -i "s/^DB_USERNAME=.*$/DB_USERNAME=$DB_USER/" .env
    sed -i "s/^DB_PASSWORD=.*$/DB_PASSWORD=$DB_PASS/" .env
fi

# Clear config cache
php artisan config:clear

# Attempt to create database if it doesn't exist
echo "Attempting to create database '$DB_NAME' if it doesn't exist..."
# Try using mysql command line if available
if command -v mysql &> /dev/null; then
    mysql -u"$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;" 2>/dev/null || echo "Could not create database automatically. Please ensure '$DB_NAME' exists."
else
    echo "'mysql' command not found. Please ensure database '$DB_NAME' exists manually."
fi

# 5. Run Migrations
echo "Running Migrations..."
php artisan migrate --force

# 6. Link Storage
if [ ! -L "public/storage" ]; then
    echo "Linking Storage..."
    php artisan storage:link
else
    echo "Storage already linked."
fi

# 7. Fix Permissions
echo "Fixing permissions..."
chmod -R 775 storage bootstrap/cache

# 8. Install Node dependencies
echo "Installing NPM dependencies..."
npm install

# 9. Build Assets
echo "Building Assets..."
npm run build

echo "Setup complete! You can now run 'php artisan serve' to start the application."
