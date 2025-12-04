# Laravel Application Deployment Checklist

## Files to Upload (All files in project root except those below)

### Files/Paths to EXCLUDE from upload:
- .git/
- .gitignore
- .env (include .env but with production settings)
- node_modules/
- tests/
- phpunit.xml
- README.md (if exists)
- composer.json
- composer.lock
- package.json
- package-lock.json
- webpack.mix.js
- vite.config.js
- any local development config files

### Essential Files/Directories to INCLUDE:
- app/
- bootstrap/
- config/
- database/
- public/ (critical - this serves web content)
- resources/
- routes/
- storage/ (with proper permissions after upload)
- vendor/ (if not present, run composer install on server)
- .env (with production settings)
- artisan
- composer.json (for reference)
- composer.lock (for reference)
- server.php
- DEPLOYMENT_INSTRUCTIONS.md

## Important Directories After Upload:
- public/ -> Should be document root for web server
- storage/ -> Must be writable by web server
- bootstrap/cache/ -> Must be writable by web server

## Post-Upload Steps:
1. chmod 755 storage/ -R (on Linux servers)
2. chmod 755 bootstrap/cache/ -R (on Linux servers)
3. php artisan config:cache
4. php artisan route:cache
5. php artisan view:cache