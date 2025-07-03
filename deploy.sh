#!/bin/bash

# Laravel Ebook App - Hostinger Deployment Script
# Run this script after uploading files to Hostinger

echo "🚀 Starting Laravel Ebook App Deployment..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Make sure you're in the Laravel project root."
    exit 1
fi

# Check if .env file exists
if [ ! -f ".env" ]; then
    echo "❌ Error: .env file not found. Please create it first."
    exit 1
fi

echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

if [ $? -ne 0 ]; then
    echo "❌ Error: Composer install failed"
    exit 1
fi

echo "🔧 Setting proper permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env

echo "🗄️ Running database migrations..."
php artisan migrate --force

if [ $? -ne 0 ]; then
    echo "❌ Error: Database migration failed"
    exit 1
fi

echo "🌱 Seeding database..."
php artisan db:seed --force

echo "🧹 Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed successfully!"
echo ""
echo "🔍 Next steps:"
echo "1. Test your application at your domain"
echo "2. Verify Stripe payments are working"
echo "3. Check email functionality"
echo "4. Monitor error logs if needed"
echo ""
echo "📝 If you encounter issues:"
echo "- Check file permissions (755 for directories, 644 for files)"
echo "- Verify database credentials in .env"
echo "- Ensure APP_KEY is generated"
echo "- Check storage directory is writable" 