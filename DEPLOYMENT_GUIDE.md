# Laravel Ebook App - Hostinger Deployment Guide

## Prerequisites
- Hostinger hosting account with PHP 8.2+ support
- MySQL database created in Hostinger
- Domain name configured
- FTP/SFTP access or File Manager access

## Step 1: Prepare Your Local Project

### 1.1 Create Production Environment File
Create a `.env` file in your project root with the following configuration:

```env
APP_NAME="Ebook App"
APP_ENV=production
APP_KEY=your_generated_key_here
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=your_hostinger_db_host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Stripe Configuration
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_stripe_webhook_secret
```

### 1.2 Generate Application Key
Run this command locally to generate your APP_KEY:
```bash
php artisan key:generate
```

### 1.3 Build Assets for Production
```bash
npm install
npm run build
```

## Step 2: Upload Files to Hostinger

### 2.1 Upload via FTP/SFTP
1. Connect to your Hostinger hosting via FTP/SFTP
2. Navigate to the `public_html` directory
3. Upload all project files EXCEPT the following:
   - `node_modules/` (don't upload)
   - `.git/` (don't upload)
   - `storage/logs/` (don't upload, but keep the directory structure)
   - `storage/framework/cache/` (don't upload, but keep the directory structure)
   - `storage/framework/sessions/` (don't upload, but keep the directory structure)
   - `storage/framework/views/` (don't upload, but keep the directory structure)

### 2.2 Alternative: Upload via File Manager
1. Go to Hostinger Control Panel → File Manager
2. Navigate to `public_html`
3. Upload your project files as a ZIP and extract them

## Step 3: Configure Web Server

### 3.1 Set Document Root
Your Laravel application's `public` folder should be the document root. In Hostinger:

1. Go to Hostinger Control Panel → Domains
2. Click on your domain → Manage
3. Set the document root to: `public_html/public`

### 3.2 Create .htaccess File
If not already present, create a `.htaccess` file in the `public` directory:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## Step 4: Install Dependencies and Configure

### 4.1 Install Composer Dependencies
Via SSH (if available) or via Hostinger's Terminal:
```bash
cd /home/username/public_html
composer install --optimize-autoloader --no-dev
```

### 4.2 Set Proper Permissions
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env
```

### 4.3 Run Database Migrations
```bash
php artisan migrate --force
```

### 4.4 Seed Database (if needed)
```bash
php artisan db:seed --force
```

### 4.5 Clear and Cache Configuration
```bash
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 5: Configure Email and Stripe

### 5.1 Email Configuration
Update your `.env` file with your email settings. For Hostinger, you can use:
- SMTP Host: `smtp.hostinger.com`
- Port: `587`
- Username: Your Hostinger email
- Password: Your email password

### 5.2 Stripe Configuration
1. Get your Stripe keys from your Stripe dashboard
2. Update the `.env` file with your Stripe configuration
3. Set up webhook endpoint in Stripe dashboard pointing to: `https://yourdomain.com/api/stripe/webhook`

## Step 6: Final Configuration

### 6.1 Update APP_URL
Make sure your `.env` file has the correct APP_URL:
```env
APP_URL=https://yourdomain.com
```

### 6.2 Test Your Application
1. Visit your domain to ensure the application loads
2. Test user registration/login
3. Test ebook browsing and purchasing
4. Test Stripe payment flow

## Troubleshooting

### Common Issues:

1. **500 Internal Server Error**
   - Check file permissions (755 for directories, 644 for files)
   - Check `.env` file exists and has correct permissions
   - Check storage directory is writable

2. **Database Connection Error**
   - Verify database credentials in `.env`
   - Ensure database exists and user has proper permissions
   - Check if database host is correct

3. **Asset Loading Issues**
   - Ensure `npm run build` was run
   - Check if `public/build` directory exists
   - Verify asset paths in your views

4. **Stripe Payment Issues**
   - Verify Stripe keys are correct
   - Check webhook endpoint is accessible
   - Ensure HTTPS is enabled

### Performance Optimization:

1. **Enable OPcache** (if available):
   ```php
   // In php.ini or via .htaccess
   opcache.enable=1
   opcache.memory_consumption=128
   opcache.interned_strings_buffer=8
   opcache.max_accelerated_files=4000
   ```

2. **Enable Gzip Compression**:
   ```apache
   # In .htaccess
   <IfModule mod_deflate.c>
       AddOutputFilterByType DEFLATE text/plain
       AddOutputFilterByType DEFLATE text/html
       AddOutputFilterByType DEFLATE text/xml
       AddOutputFilterByType DEFLATE text/css
       AddOutputFilterByType DEFLATE application/xml
       AddOutputFilterByType DEFLATE application/xhtml+xml
       AddOutputFilterByType DEFLATE application/rss+xml
       AddOutputFilterByType DEFLATE application/javascript
       AddOutputFilterByType DEFLATE application/x-javascript
   </IfModule>
   ```

## Security Checklist

- [ ] APP_DEBUG is set to false
- [ ] APP_KEY is properly generated
- [ ] Database credentials are secure
- [ ] Stripe keys are production keys
- [ ] File permissions are properly set
- [ ] HTTPS is enabled
- [ ] .env file is not accessible via web

## Support

If you encounter issues during deployment:
1. Check Hostinger's error logs
2. Verify all configuration settings
3. Test with a simple PHP file first
4. Contact Hostinger support if server-related issues persist

Your Laravel ebook application should now be successfully deployed on Hostinger! 