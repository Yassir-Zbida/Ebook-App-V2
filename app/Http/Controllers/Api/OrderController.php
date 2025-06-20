<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ebook;
use App\Models\UserEbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Get user's order history
     */
    public function userOrders(Request $request)
    {
        $user = $request->user();

        $query = $user->purchasedEbooks()->with(['categories' => function($query) {
            $query->whereNull('parent_id')->orderBy('sort_order');
        }]);

        // Apply sorting
        $sortBy = $request->get('sort_by', 'purchased_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy('user_ebooks.' . $sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 12);
        $orders = $query->paginate($perPage);

        $orders->getCollection()->transform(function ($ebook) {
            return [
                'order_id' => $ebook->pivot->id,
                'ebook_id' => $ebook->id,
                'title' => $ebook->title,
                'description' => $ebook->description,
                'price' => $ebook->price,
                'cover_image' => $ebook->cover_image,
                'purchase_price' => $ebook->pivot->purchase_price,
                'purchased_at' => $ebook->pivot->purchased_at,
                'categories_count' => $ebook->categories->count(),
                'resources_count' => $ebook->getAllResourcesCount(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Get specific order details
     */
    public function userOrderDetail(Request $request, $orderId)
    {
        $user = $request->user();

        $order = UserEbook::where('id', $orderId)
            ->where('user_id', $user->id)
            ->with(['ebook.categories' => function($query) {
                $query->whereNull('parent_id')->orderBy('sort_order');
            }])
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order_id' => $order->id,
                'ebook' => [
                    'id' => $order->ebook->id,
                    'title' => $order->ebook->title,
                    'description' => $order->ebook->description,
                    'price' => $order->ebook->price,
                    'cover_image' => $order->ebook->cover_image,
                    'categories_count' => $order->ebook->categories->count(),
                    'resources_count' => $order->ebook->getAllResourcesCount(),
                ],
                'purchase_price' => $order->purchase_price,
                'purchased_at' => $order->purchased_at,
                'created_at' => $order->created_at,
            ]
        ]);
    }

    /**
     * Get user's shopping cart
     */
    public function cart(Request $request)
    {
        // This would be implemented when you add a cart table
        return response()->json([
            'success' => true,
            'data' => [
                'cart' => [],
                'total' => 0,
                'message' => 'Shopping cart feature coming soon'
            ]
        ]);
    }

    /**
     * Add ebook to cart
     */
    public function addToCart(Request $request)
    {
        // This would be implemented when you add a cart table
        return response()->json([
            'success' => false,
            'message' => 'Shopping cart feature coming soon'
        ], 501);
    }

    /**
     * Update cart
     */
    public function updateCart(Request $request)
    {
        // This would be implemented when you add a cart table
        return response()->json([
            'success' => false,
            'message' => 'Shopping cart feature coming soon'
        ], 501);
    }

    /**
     * Remove ebook from cart
     */
    public function removeFromCart(Request $request, Ebook $ebook)
    {
        // This would be implemented when you add a cart table
        return response()->json([
            'success' => false,
            'message' => 'Shopping cart feature coming soon'
        ], 501);
    }

    /**
     * Clear cart
     */
    public function clearCart(Request $request)
    {
        // This would be implemented when you add a cart table
        return response()->json([
            'success' => false,
            'message' => 'Shopping cart feature coming soon'
        ], 501);
    }

    /**
     * Process checkout
     */
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ebook_ids' => 'required|array',
            'ebook_ids.*' => 'exists:ebooks,id',
            'payment_method' => 'required|string|in:stripe,paypal,cash',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $ebookIds = $request->ebook_ids;

        // Check if user already purchased any of these ebooks
        $alreadyPurchased = $user->purchasedEbooks()
            ->whereIn('ebook_id', $ebookIds)
            ->pluck('ebook_id')
            ->toArray();

        if (!empty($alreadyPurchased)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already purchased some of these ebooks',
                'already_purchased' => $alreadyPurchased
            ], 400);
        }

        // Get ebooks and calculate total
        $ebooks = Ebook::whereIn('id', $ebookIds)
            ->where('is_active', true)
            ->get();

        if ($ebooks->count() !== count($ebookIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Some ebooks are not available'
            ], 400);
        }

        $total = $ebooks->sum('price');

        try {
            DB::beginTransaction();

            // Create purchase records
            $purchases = [];
            foreach ($ebooks as $ebook) {
                $purchases[] = [
                    'user_id' => $user->id,
                    'ebook_id' => $ebook->id,
                    'purchase_price' => $ebook->price,
                    'purchased_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            UserEbook::insert($purchases);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase completed successfully',
                'data' => [
                    'total_amount' => $total,
                    'items_count' => count($ebooks),
                    'purchased_ebooks' => $ebooks->map(function ($ebook) {
                        return [
                            'id' => $ebook->id,
                            'title' => $ebook->title,
                            'price' => $ebook->price,
                        ];
                    }),
                    'order_id' => uniqid('ORD-'),
                    'purchased_at' => now(),
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process purchase',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new order
     */
    public function store(Request $request)
    {
        return $this->checkout($request);
    }

    /**
     * Get specific order
     */
    public function show(Request $request, $orderId)
    {
        return $this->userOrderDetail($request, $orderId);
    }
} 