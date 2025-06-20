<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserEbook;
use App\Models\Ebook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Get sales analytics
     */
    public function sales(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = Carbon::now()->subDays($period);

        // Total sales
        $totalSales = UserEbook::where('purchased_at', '>=', $startDate)
            ->sum('purchase_price');

        // Sales by day
        $salesByDay = UserEbook::where('purchased_at', '>=', $startDate)
            ->selectRaw('DATE(purchased_at) as date, COUNT(*) as orders, SUM(purchase_price) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top selling ebooks
        $topEbooks = UserEbook::where('purchased_at', '>=', $startDate)
            ->with('ebook')
            ->selectRaw('ebook_id, COUNT(*) as sales_count, SUM(purchase_price) as revenue')
            ->groupBy('ebook_id')
            ->orderByDesc('sales_count')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'ebook_id' => $item->ebook_id,
                    'title' => $item->ebook->title,
                    'sales_count' => $item->sales_count,
                    'revenue' => $item->revenue,
                ];
            });

        // Sales by user type
        $salesByUserType = UserEbook::where('purchased_at', '>=', $startDate)
            ->join('users', 'user_ebooks.user_id', '=', 'users.id')
            ->selectRaw('users.role, COUNT(*) as orders, SUM(user_ebooks.purchase_price) as revenue')
            ->groupBy('users.role')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'period' => $period . ' days',
                'start_date' => $startDate->toDateString(),
                'total_sales' => $totalSales,
                'total_orders' => UserEbook::where('purchased_at', '>=', $startDate)->count(),
                'sales_by_day' => $salesByDay,
                'top_ebooks' => $topEbooks,
                'sales_by_user_type' => $salesByUserType,
            ]
        ]);
    }

    /**
     * Get ebook analytics
     */
    public function ebooks(Request $request)
    {
        // Ebook statistics
        $totalEbooks = Ebook::count();
        $activeEbooks = Ebook::where('is_active', true)->count();
        $inactiveEbooks = Ebook::where('is_active', false)->count();

        // Ebooks by price range
        $priceRanges = [
            '0-10' => Ebook::where('price', '>=', 0)->where('price', '<=', 10)->count(),
            '10-25' => Ebook::where('price', '>', 10)->where('price', '<=', 25)->count(),
            '25-50' => Ebook::where('price', '>', 25)->where('price', '<=', 50)->count(),
            '50+' => Ebook::where('price', '>', 50)->count(),
        ];

        // Most popular ebooks (by purchase count)
        $popularEbooks = Ebook::withCount('purchasers')
            ->orderByDesc('purchasers_count')
            ->limit(10)
            ->get()
            ->map(function ($ebook) {
                return [
                    'id' => $ebook->id,
                    'title' => $ebook->title,
                    'price' => $ebook->price,
                    'is_active' => $ebook->is_active,
                    'purchasers_count' => $ebook->purchasers_count,
                    'categories_count' => $ebook->categories()->count(),
                    'resources_count' => $ebook->getAllResourcesCount(),
                ];
            });

        // Ebooks with most categories
        $ebooksWithMostCategories = Ebook::withCount('categories')
            ->orderByDesc('categories_count')
            ->limit(10)
            ->get()
            ->map(function ($ebook) {
                return [
                    'id' => $ebook->id,
                    'title' => $ebook->title,
                    'categories_count' => $ebook->categories_count,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_ebooks' => $totalEbooks,
                'active_ebooks' => $activeEbooks,
                'inactive_ebooks' => $inactiveEbooks,
                'price_ranges' => $priceRanges,
                'popular_ebooks' => $popularEbooks,
                'ebooks_with_most_categories' => $ebooksWithMostCategories,
            ]
        ]);
    }

    /**
     * Get user analytics
     */
    public function users(Request $request)
    {
        // User statistics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();
        $adminUsers = User::where('role', 'admin')->count();
        $customerUsers = User::where('role', 'customer')->count();

        // Users by registration period
        $period = $request->get('period', '30'); // days
        $startDate = Carbon::now()->subDays($period);
        
        $newUsers = User::where('created_at', '>=', $startDate)->count();

        // Top customers (by purchase count)
        $topCustomers = User::withCount('purchasedEbooks')
            ->where('role', 'customer')
            ->orderByDesc('purchased_ebooks_count')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_active' => $user->is_active,
                    'purchased_ebooks_count' => $user->purchased_ebooks_count,
                    'created_at' => $user->created_at,
                ];
            });

        // Users with most purchases
        $usersWithMostPurchases = User::withSum('purchasedEbooks', 'purchase_price')
            ->where('role', 'customer')
            ->orderByDesc('purchased_ebooks_sum_purchase_price')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'total_spent' => $user->purchased_ebooks_sum_purchase_price ?? 0,
                    'purchased_ebooks_count' => $user->purchasedEbooks()->count(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'inactive_users' => $inactiveUsers,
                'admin_users' => $adminUsers,
                'customer_users' => $customerUsers,
                'new_users_last_' . $period . '_days' => $newUsers,
                'top_customers' => $topCustomers,
                'users_with_most_purchases' => $usersWithMostPurchases,
            ]
        ]);
    }
} 