<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\EbookCategoryController;
use App\Http\Controllers\CategoryResourceController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    return redirect('/login');
});

// Guest routes (not authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Authenticated routes with role middleware
Route::middleware(['auth', 'role'])->group(function () {
    
    // Common authenticated routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Ebook routes - accessible to all authenticated users
    Route::get('/ebooks', [EbookController::class, 'index'])->name('ebooks.index');
    Route::get('/ebooks/{ebook}', [EbookController::class, 'show'])->name('ebooks.show');
    Route::get('/ebooks/{ebook}/explore/{category?}', [EbookCategoryController::class, 'explore'])
         ->name('ebooks.explore');
    Route::get('/ebooks/{ebook}/download', [EbookController::class, 'download'])->name('ebooks.download');
    
    // Resource routes - accessible to all authenticated users
    Route::get('/resources/{resource}', [CategoryResourceController::class, 'show'])
         ->name('category-resources.show');
    Route::get('/resources/{resource}/download', [CategoryResourceController::class, 'download'])
         ->name('category-resources.download');
    
    // Admin routes - check role in controller or add middleware check
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Ebook management
        Route::get('/ebooks/create', [EbookController::class, 'create'])->name('ebooks.create');
        Route::post('/ebooks', [EbookController::class, 'store'])->name('ebooks.store');
        Route::get('/ebooks/{ebook}/edit', [EbookController::class, 'edit'])->name('ebooks.edit');
        Route::put('/ebooks/{ebook}', [EbookController::class, 'update'])->name('ebooks.update');
        Route::delete('/ebooks/{ebook}', [EbookController::class, 'destroy'])->name('ebooks.destroy');
        
        // Category management
        Route::get('/ebooks/{ebook}/categories', [EbookCategoryController::class, 'index'])
             ->name('ebook-categories.index');
        Route::get('/ebooks/{ebook}/categories/create', [EbookCategoryController::class, 'create'])
             ->name('ebook-categories.create');
        Route::post('/ebooks/{ebook}/categories', [EbookCategoryController::class, 'store'])
             ->name('ebook-categories.store');
        Route::get('/ebooks/{ebook}/categories/{category}/edit', [EbookCategoryController::class, 'edit'])
             ->name('ebook-categories.edit');
        Route::put('/ebooks/{ebook}/categories/{category}', [EbookCategoryController::class, 'update'])
             ->name('ebook-categories.update');
        Route::delete('/ebooks/{ebook}/categories/{category}', [EbookCategoryController::class, 'destroy'])
             ->name('ebook-categories.destroy');
        
        // Resource management
        Route::get('/categories/{category}/resources/create', [CategoryResourceController::class, 'create'])
             ->name('category-resources.create');
        Route::post('/categories/{category}/resources', [CategoryResourceController::class, 'store'])
             ->name('category-resources.store');
        Route::get('/resources/{resource}/edit', [CategoryResourceController::class, 'edit'])
             ->name('category-resources.edit');
        Route::put('/resources/{resource}', [CategoryResourceController::class, 'update'])
             ->name('category-resources.update');
        Route::delete('/resources/{resource}', [CategoryResourceController::class, 'destroy'])
             ->name('category-resources.destroy');
        
        // AJAX endpoints for dynamic forms
        Route::get('/api/content-types/{id}/form-fields', [CategoryResourceController::class, 'getFormFields'])
             ->name('api.content-types.form-fields');
    });
});