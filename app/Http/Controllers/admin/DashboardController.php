<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ebook;
use App\Models\Order;
use App\Models\UserEbook;
use App\Models\Review;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Sales statistics
        $totalSales = UserEbook::count();
        $salesThisMonth = UserEbook::whereMonth('purchased_at', now()->month)->count();
        $salesLastMonth = UserEbook::whereMonth('purchased_at', now()->subMonth()->month)->count();
        $salesGrowth = $salesLastMonth > 0 ? (($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100 : 0;

        // Revenue statistics
        $totalRevenue = UserEbook::sum('purchase_price');
        $revenueThisMonth = UserEbook::whereMonth('purchased_at', now()->month)->sum('purchase_price');
        $revenueLastMonth = UserEbook::whereMonth('purchased_at', now()->subMonth()->month)->sum('purchase_price');
        $revenueGrowth = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : 0;

        // User statistics
        $totalUsers = User::where('role', 'customer')->count();
        $activeUsers = User::where('role', 'customer')->where('is_active', true)->count();
        $userGrowth = 0; // You can implement this if you want

        // Conversion rate (users who made at least one purchase)
        $usersWithPurchases = User::where('role', 'customer')->whereHas('purchasedEbooks')->count();
        $conversionRate = $totalUsers > 0 ? ($usersWithPurchases / $totalUsers) * 100 : 0;
        $conversionGrowth = 0; // You can implement this if you want

        // Ebooks statistics
        $totalEbooks = Ebook::count();
        $activeEbooks = Ebook::where('is_active', true)->count();
        $inactiveEbooks = Ebook::where('is_active', false)->count();

        // Recent activities (last 5 purchases and reviews)
        $recentPurchases = UserEbook::with(['user', 'ebook'])->latest('purchased_at')->limit(5)->get();
        $recentReviews = Review::with(['user', 'ebook'])->statusApproved()->latest()->limit(5)->get();

        // Top selling ebooks
        $topSellingEbooks = Ebook::withCount('purchasers')->orderByDesc('purchasers_count')->limit(5)->get();

        return view('admin.index', compact(
            'totalSales',
            'salesGrowth',
            'totalRevenue',
            'revenueGrowth',
            'totalUsers',
            'activeUsers',
            'userGrowth',
            'conversionRate',
            'conversionGrowth',
            'totalEbooks',
            'activeEbooks',
            'inactiveEbooks',
            'recentPurchases',
            'recentReviews',
            'topSellingEbooks'
        ));
    }
}