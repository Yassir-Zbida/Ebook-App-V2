<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserEbook;
use App\Models\User;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = UserEbook::with(['user', 'ebook']);

        // Apply filters
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('ebook_id')) {
            $query->where('ebook_id', $request->ebook_id);
        }

        if ($request->has('date_from')) {
            $query->where('purchased_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('purchased_at', '<=', $request->date_to);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'purchased_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 20);
        $orders = $query->paginate($perPage);

        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'user' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'ebook' => [
                    'id' => $order->ebook->id,
                    'title' => $order->ebook->title,
                    'price' => $order->ebook->price,
                ],
                'purchase_price' => $order->purchase_price,
                'purchased_at' => $order->purchased_at,
                'created_at' => $order->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Display the specified order
     */
    public function show(UserEbook $order)
    {
        $order->load(['user', 'ebook']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'user' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'role' => $order->user->role,
                ],
                'ebook' => [
                    'id' => $order->ebook->id,
                    'title' => $order->ebook->title,
                    'description' => $order->ebook->description,
                    'price' => $order->ebook->price,
                    'cover_image' => $order->ebook->cover_image,
                ],
                'purchase_price' => $order->purchase_price,
                'purchased_at' => $order->purchased_at,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ]
        ]);
    }

    /**
     * Update order status (placeholder for future implementation)
     */
    public function updateStatus(Request $request, UserEbook $order)
    {
        // This would be implemented when you add order status functionality
        return response()->json([
            'success' => false,
            'message' => 'Order status management coming soon'
        ], 501);
    }
} 