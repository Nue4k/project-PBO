@echo off
echo Preparing Laravel Application for Deployment...
echo.

echo Step 1: Installing production dependencies...
composer install --optimize-autoloader --no-dev
if errorlevel 1 goto error

echo.
echo Step 2: Generating application key...
php artisan key:generate
if errorlevel 1 goto error

echo.
echo Step 3: Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo.
echo Step 4: Creating deployment archive...
if exist "laravel-deployment.zip" del "laravel-deployment.zip"
"C:\Program Files\7-Zip\7z.exe" a -tzip laravel-deployment.zip . -xr!.git -xr!node_modules -xr!.env.local -xr!.env.*.local -xr!.env.testing -xr!storage/logs/* -xr!tests/*
if not exist "laravel-deployment.zip" (
  echo Creating archive with default Windows tools...
  powershell Compress-Archive -Path . -DestinationPath laravel-deployment.zip -Update
)

echo.
echo Deployment package created: laravel-deployment.zip
echo.

echo Please note:
echo 1. Upload the contents of this directory to your web server
echo 2. Update the .env file with your production settings
echo 3. Run 'php artisan migrate' after uploading
echo 4. Set proper permissions for storage/ and bootstrap/cache/ directories
goto end

:error
echo An error occurred during preparation.
echo Please check that you have PHP and Composer installed and accessible from command line.

:end
pause