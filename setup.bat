@echo off
setlocal enabledelayedexpansion

echo Starting setup for WebAtoon (Windows Edition)...

:: 1. Install PHP dependencies
echo Installing Composer dependencies...
call composer install
if %errorlevel% neq 0 exit /b %errorlevel%

:: 2. Copy .env file
if not exist .env (
    echo Creating .env file...
    copy .env.example .env
) else (
    echo .env file already exists.
)

:: 3. Generate App Key
echo Generating Application Key...
call php artisan key:generate

:: 4. Configure Database
echo Database Configuration
set /p DB_NAME="Enter Database Name (default: webatoon): "
if "!DB_NAME!"=="" set DB_NAME=webatoon

set /p DB_USER="Enter Database User (default: root): "
if "!DB_USER!"=="" set DB_USER=root

set /p DB_PASS="Enter Database Password: "

:: Update .env (Robust search and replace using PowerShell)
powershell -Command "(Get-Content .env) -replace '^DB_DATABASE=.*$', 'DB_DATABASE=!DB_NAME!' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '^DB_USERNAME=.*$', 'DB_USERNAME=!DB_USER!' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace '^DB_PASSWORD=.*$', 'DB_PASSWORD=!DB_PASS!' | Set-Content .env"

:: Clear config cache to ensure new .env values are used
call php artisan config:clear

:: Attempt to create database
echo Attempting to create database '!DB_NAME!' if it doesn't exist...
mysql -u"!DB_USER!" -p"!DB_PASS!" -e "CREATE DATABASE IF NOT EXISTS !DB_NAME!;"
if %errorlevel% neq 0 (
    echo.
    echo ⚠️  WARNING: Could not create database automatically.
    echo This is usually because:
    echo 1. The 'mysql' command is not in your PATH.
    echo 2. The credentials - User/Password - provided are incorrect.
    echo.
)

:: 5. Run Migrations
echo Running Migrations...
call php artisan migrate --force

:: 6. Link Storage
if not exist "public\storage" (
    echo Linking Storage...
    call php artisan storage:link
) else (
    echo Storage already linked.
)

:: 7. Install Node dependencies
echo Installing NPM dependencies...
call npm install

:: 8. Build Assets
echo Building Assets...
call npm run build

echo Setup complete! You can now run 'php artisan serve' to start the application.
pause
