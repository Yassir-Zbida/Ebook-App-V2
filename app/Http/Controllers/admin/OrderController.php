<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'orderItems']);

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('payment_status')) {
            $query->byPaymentStatus($request->payment_status);
        }

        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::byStatus('pending')->count(),
            'processing' => Order::byStatus('processing')->count(),
            'completed' => Order::byStatus('completed')->count(),
            'cancelled' => Order::byStatus('cancelled')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
        ];

        // Get users for filter
        $users = User::customers()->active()->get(['id', 'name', 'email']);

        return view('admin.orders.index', compact('orders', 'stats', 'users'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'orderItems.ebook']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
            'notes' => 'nullable|string|max:500',
        ]);

        $order->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'order' => $order->fresh()->load('user'),
        ]);
    }

    /**
     * Get order details for modal
     */
    public function getOrderDetails(Order $order)
    {
        $order->load(['user', 'orderItems.ebook']);
        
        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }

    /**
     * Get orders statistics
     */
    public function getStats()
    {
        $stats = [
            'total' => Order::count(),
            'pending' => Order::byStatus('pending')->count(),
            'processing' => Order::byStatus('processing')->count(),
            'completed' => Order::byStatus('completed')->count(),
            'cancelled' => Order::byStatus('cancelled')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'monthly_revenue' => Order::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
        ];

        return response()->json($stats);
    }
} 