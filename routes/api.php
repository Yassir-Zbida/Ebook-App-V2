<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EbookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ResourceController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required)
Route::prefix('v1')->group(function () {
    
    // Authentication routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    
    // Public ebook browsing
    Route::get('/ebooks', [EbookController::class, 'index']);
    Route::get('/ebooks/{ebook}', [EbookController::class, 'show']);
    Route::get('/ebooks/{ebook}/categories', [EbookController::class, 'categories']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/categories/{category}/resources', [CategoryController::class, 'resources']);
    
    // Search and filtering
    Route::get('/search/ebooks', [EbookController::class, 'search']);
    Route::get('/search/categories', [CategoryController::class, 'search']);
    
    // Featured and popular content
    Route::get('/featured/ebooks', [EbookController::class, 'featured']);
    Route::get('/popular/ebooks', [EbookController::class, 'popular']);
    
    // Public reviews (read-only)
    Route::get('/ebooks/{ebook}/reviews', [ReviewController::class, 'index']);
});

// Protected routes (authentication required)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // User profile and account management
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::put('/user/password', [UserController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User's purchased ebooks
    Route::get('/user/ebooks', [UserController::class, 'purchasedEbooks']);
    Route::get('/user/ebooks/{ebook}', [UserController::class, 'purchasedEbookDetail']);
    Route::get('/user/ebooks/{ebook}/categories', [UserController::class, 'purchasedEbookCategories']);
    Route::get('/user/ebooks/{ebook}/categories/{category}/resources', [UserController::class, 'purchasedEbookResources']);
    
    // Purchase history
    Route::get('/user/orders', [OrderController::class, 'userOrders']);
    Route::get('/user/orders/{order}', [OrderController::class, 'userOrderDetail']);
    
    // Shopping cart management
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'addItem']);
    Route::delete('/cart/items/{itemId}', [CartController::class, 'removeItem']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon']);
    Route::delete('/cart/remove-coupon', [CartController::class, 'removeCoupon']);
    
    // Wishlist management
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/{ebook}', [WishlistController::class, 'add']);
    Route::delete('/wishlist/{ebook}', [WishlistController::class, 'remove']);
    Route::get('/wishlist/{ebook}/check', [WishlistController::class, 'check']);
    Route::post('/wishlist/{ebook}/move-to-cart', [WishlistController::class, 'moveToCart']);
    Route::delete('/wishlist/clear', [WishlistController::class, 'clear']);
    Route::get('/wishlist/count', [WishlistController::class, 'count']);
    
    // Checkout and purchase
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    
    // Reviews and ratings (authenticated users)
    Route::post('/ebooks/{ebook}/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
    Route::get('/user/reviews', [ReviewController::class, 'userReviews']);
});

// Admin routes (admin role required)
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    
    // Ebook management
    Route::apiResource('ebooks', \App\Http\Controllers\Api\Admin\EbookController::class);
    Route::post('/ebooks/{ebook}/publish', [\App\Http\Controllers\Api\Admin\EbookController::class, 'publish']);
    Route::post('/ebooks/{ebook}/unpublish', [\App\Http\Controllers\Api\Admin\EbookController::class, 'unpublish']);
    
    // Category management
    Route::apiResource('categories', \App\Http\Controllers\Api\Admin\CategoryController::class);
    Route::post('/categories/{category}/resources', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'addResource']);
    
    // Resource management
    Route::apiResource('resources', \App\Http\Controllers\Api\Admin\ResourceController::class);
    
    // Order management
    Route::get('/orders', [\App\Http\Controllers\Api\Admin\OrderController::class, 'index']);
    Route::get('/orders/{order}', [\App\Http\Controllers\Api\Admin\OrderController::class, 'show']);
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Api\Admin\OrderController::class, 'updateStatus']);
    
    // User management
    Route::get('/users', [\App\Http\Controllers\Api\Admin\UserController::class, 'index']);
    Route::get('/users/{user}', [\App\Http\Controllers\Api\Admin\UserController::class, 'show']);
    Route::put('/users/{user}', [\App\Http\Controllers\Api\Admin\UserController::class, 'update']);
    Route::delete('/users/{user}', [\App\Http\Controllers\Api\Admin\UserController::class, 'destroy']);
    
    // Analytics and reports
    Route::get('/analytics/sales', [\App\Http\Controllers\Api\Admin\AnalyticsController::class, 'sales']);
    Route::get('/analytics/ebooks', [\App\Http\Controllers\Api\Admin\AnalyticsController::class, 'ebooks']);
    Route::get('/analytics/users', [\App\Http\Controllers\Api\Admin\AnalyticsController::class, 'users']);
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
}); 