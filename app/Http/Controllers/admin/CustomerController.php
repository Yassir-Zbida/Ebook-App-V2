<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request): View
    {
        $query = User::customers()->with(['orders']);

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $customers = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total' => User::customers()->count(),
            'active' => User::customers()->active()->count(),
            'inactive' => User::customers()->where('is_active', false)->count(),
            'new_this_month' => User::customers()->whereMonth('created_at', now()->month)->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    /**
     * Display the specified customer
     */
    public function show(User $customer): View
    {
        $customer->load(['orders.orderItems.ebook']);
        
        // Get customer statistics
        $customerStats = [
            'total_orders' => $customer->total_orders,
            'total_spent' => $customer->total_spent,
            'last_order' => $customer->last_order,
            'average_order_value' => $customer->orders()->where('status', 'completed')->avg('total_amount') ?? 0,
        ];

        return view('admin.customers.show', compact('customer', 'customerStats'));
    }

    /**
     * Update customer status
     */
    public function updateStatus(Request $request, User $customer)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $customer->update([
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer status updated successfully',
            'customer' => $customer->fresh(),
        ]);
    }

    /**
     * Get customer details for modal
     */
    public function getCustomerDetails(User $customer)
    {
        $customer->load(['orders.orderItems.ebook']);
        
        $customerStats = [
            'total_orders' => $customer->total_orders,
            'total_spent' => $customer->total_spent,
            'last_order' => $customer->last_order,
            'average_order_value' => $customer->orders()->where('status', 'completed')->avg('total_amount') ?? 0,
        ];
        
        return response()->json([
            'success' => true,
            'customer' => $customer,
            'stats' => $customerStats,
        ]);
    }

    /**
     * Get customer orders
     */
    public function getCustomerOrders(User $customer)
    {
        $orders = $customer->orders()
            ->with(['orderItems.ebook'])
            ->latest()
            ->paginate(10);
        
        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }

    /**
     * Get customers statistics
     */
    public function getStats()
    {
        $stats = [
            'total' => User::customers()->count(),
            'active' => User::customers()->active()->count(),
            'inactive' => User::customers()->where('is_active', false)->count(),
            'new_this_month' => User::customers()->whereMonth('created_at', now()->month)->count(),
            'new_this_week' => User::customers()->whereWeek('created_at', now()->week)->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'monthly_revenue' => Order::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
        ];

        return response()->json($stats);
    }
} 