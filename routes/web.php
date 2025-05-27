<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\EbookCategoryController;
use App\Http\Controllers\CategoryResourceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
Route::middleware(['role'])->group(function () {
    
    // Common authenticated routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');
    
    // Single Dashboard Route - Same URL for all users, different views based on role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Ebook management
    Route::resource('ebooks', \App\Http\Controllers\Admin\EbookController::class);
});

// Debug route to test form submission
Route::post('/debug-form', function(Request $request) {
    $result = [
        'status' => 'debug_complete',
        'all_input' => [],
        'categories_analysis' => []
    ];
    
    // Collect all input data
    $allInput = $request->all();
    foreach ($allInput as $key => $value) {
        if (is_string($value)) {
            $result['all_input'][$key] = substr($value, 0, 100);
        } elseif (is_array($value)) {
            $result['all_input'][$key] = 'ARRAY with ' . count($value) . ' items';
            foreach ($value as $subKey => $subValue) {
                if (is_string($subValue)) {
                    $result['all_input'][$key . '[' . $subKey . ']'] = substr($subValue, 0, 50);
                } elseif (is_array($subValue)) {
                    $result['all_input'][$key . '[' . $subKey . ']'] = 'ARRAY with ' . count($subValue) . ' items';
                } else {
                    $result['all_input'][$key . '[' . $subKey . ']'] = gettype($subValue);
                }
            }
        } else {
            $result['all_input'][$key] = gettype($value);
        }
    }
    
    // Analyze categories specifically
    $categories = $request->input('categories', []);
    $result['categories_analysis'] = [
        'type' => gettype($categories),
        'count' => is_array($categories) ? count($categories) : 'N/A',
        'is_array' => is_array($categories),
        'first_item_type' => !empty($categories) ? gettype(reset($categories)) : 'empty'
    ];
    
    if (is_array($categories) && !empty($categories)) {
        $firstCategory = reset($categories);
        if (is_array($firstCategory)) {
            $result['categories_analysis']['first_item_keys'] = array_keys($firstCategory);
        } elseif (is_string($firstCategory)) {
            $result['categories_analysis']['first_item_value'] = substr($firstCategory, 0, 50);
        }
    }
    
    return response()->json($result, 200, [], JSON_PRETTY_PRINT);
})->name('debug.form')->withoutMiddleware(['web']);