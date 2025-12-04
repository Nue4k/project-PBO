# Deployment Instructions for Laravel Application

## Prerequisites
- PHP 8.0+ installed on the server
- MySQL or compatible database
- Composer installed on the server

## Deployment Steps

### 1. Upload Files
Upload all files to your web server. Make sure to:
- Upload the entire Laravel project
- Ensure the `public` folder is accessible via web
- Protect sensitive files like `.env` with proper permissions

### 2. Set File Permissions
Ensure the following directories are writable:
```
storage/
bootstrap/cache/
```

### 3. Install Dependencies
Run the following command in your project root:
```
composer install --optimize-autoloader --no-dev
```

### 4. Generate Application Key
Run this command to generate a new application key:
```
php artisan key:generate
```

### 5. Configure Database
1. Create a database on your hosting account
2. Update the `.env` file with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=localhost (or your database host)
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 6. Run Migrations
Execute the database migrations:
```
php artisan migrate --force
```

### 7. (Optional) Seed Database
If you have seeders to populate initial data:
```
php artisan db:seed --force
```

### 8. Clear Cache
Clear any cached configuration:
```
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 9. Configure Web Server
Configure your web server to point to the `public` directory as the document root.

## Important Notes
- Keep `APP_DEBUG=false` in production
- Make sure `.env` file is not accessible via web
- Regularly backup your database and application files
- Consider using Laravel Forge, Envoyer or similar tools for easier deployments

## Troubleshooting
If you encounter issues:
1. Check error logs in `storage/logs/`
2. Verify file permissions
3. Ensure PHP extensions required by Laravel are installed
4. Confirm database credentials are correct