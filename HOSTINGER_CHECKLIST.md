# Hostinger Deployment Checklist

## Before Upload
- [ ] Generate APP_KEY locally: `php artisan key:generate`
- [ ] Build assets: `npm run build`
- [ ] Create `.env` file with production settings
- [ ] Update database credentials in `.env`
- [ ] Update Stripe keys in `.env` (production keys)
- [ ] Update APP_URL in `.env`

## Upload Process
- [ ] Upload all files to `public_html` directory
- [ ] Set document root to `public_html/public`
- [ ] Ensure `.htaccess` exists in `public` directory

## After Upload
- [ ] Run deployment script: `bash deploy.sh`
- [ ] Or manually run these commands:
  - `composer install --optimize-autoloader --no-dev`
  - `chmod -R 755 storage bootstrap/cache`
  - `chmod 644 .env`
  - `php artisan migrate --force`
  - `php artisan db:seed --force`
  - `php artisan config:cache`
  - `php artisan route:cache`
  - `php artisan view:cache`

## Configuration
- [ ] Database connection working
- [ ] Email settings configured
- [ ] Stripe webhook endpoint set up
- [ ] HTTPS enabled
- [ ] File permissions correct

## Testing
- [ ] Homepage loads
- [ ] User registration works
- [ ] User login works
- [ ] Ebook browsing works
- [ ] Cart functionality works
- [ ] Stripe payment works
- [ ] Email sending works

## Security
- [ ] APP_DEBUG=false
- [ ] .env file not accessible via web
- [ ] Production Stripe keys used
- [ ] HTTPS enabled
- [ ] File permissions secure

## Performance
- [ ] OPcache enabled (if available)
- [ ] Gzip compression enabled
- [ ] Assets cached
- [ ] Routes cached
- [ ] Views cached 