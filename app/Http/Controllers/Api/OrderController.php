<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ebook;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }
    /**
     * Get user's order history
     */
    public function userOrders(Request $request)
    {
        $user = $request->user();

        $orders = Order::where('user_id', $user->id)
            ->with(['items.ebook'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $orders->getCollection()->transform(function ($order) {
            return [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'items_count' => $order->orderItems->count(),
                'created_at' => $order->created_at,
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'ebook_id' => $item->ebook_id,
                        'ebook_title' => $item->ebook_title,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
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

        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->with(['orderItems.ebook'])
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
                'order_number' => $order->order_number,
                'status' => $order->status,
                'subtotal' => $order->subtotal,
                'discount_amount' => $order->discount_amount,
                'tax_amount' => $order->tax_amount,
                'total_amount' => $order->total_amount,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'created_at' => $order->created_at,
                'completed_at' => $order->completed_at,
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'ebook_id' => $item->ebook_id,
                        'ebook_title' => $item->ebook_title,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                        'ebook' => [
                            'id' => $item->ebook->id,
                            'title' => $item->ebook->title,
                            'description' => $item->ebook->description,
                            'cover_image' => $item->ebook->cover_image,
                        ],
                    ];
                }),
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
     * Process checkout from cart
     */
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string|in:stripe,paypal,cash',
            // 'billing_info' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        // Get user's cart
        $cart = Cart::where('session_id', $user->id)->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        // Check if user already purchased any of these ebooks
        $ebookIds = $cart->items->pluck('ebook_id')->toArray();
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

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . time() . '-' . $user->id,
                'user_id' => $user->id,
                'status' => 'pending',
                'subtotal' => $cart->subtotal,
                'discount_amount' => $cart->discount_amount,
                'tax_amount' => $cart->tax_amount,
                'total_amount' => $cart->total,
                'coupon_code' => $cart->coupon_code,
                'coupon_discount' => $cart->coupon_discount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'billing_info' => $request->billing_info,
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'ebook_id' => $cartItem->ebook_id,
                    'ebook_title' => $cartItem->ebook->title,
                    'price' => $cartItem->price,
                    'discount_amount' => $cartItem->discount_amount,
                    'subtotal' => $cartItem->subtotal,
                ]);
            }

            // Handle payment processing based on method
            if ($request->payment_method === 'stripe') {
                // For Stripe, we create the order but don't complete it until payment is confirmed
                // The frontend will need to call the Stripe payment intent endpoint
                
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully. Please complete payment.',
                    'data' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'total_amount' => $order->total_amount,
                        'items_count' => $order->orderItems->count(),
                        'status' => $order->status,
                        'payment_method' => $order->payment_method,
                        'payment_status' => $order->payment_status,
                        'created_at' => $order->created_at,
                        'next_step' => 'Create payment intent using /v1/stripe/payment-intent endpoint'
                    ]
                ], 201);
            } else {
                // For other payment methods (cash, etc.), complete the order immediately
                // Create purchase records in user_ebooks table
                foreach ($cart->items as $cartItem) {
                    DB::table('user_ebooks')->insert([
                        'user_id' => $user->id,
                        'ebook_id' => $cartItem->ebook_id,
                        'purchase_price' => $cartItem->price,
                        'purchased_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Update order status
                $order->update([
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'completed_at' => now(),
                ]);

                // Clear the cart
                $cart->items()->delete();
                $cart->delete();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully',
                    'data' => [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'total_amount' => $order->total_amount,
                        'items_count' => $order->orderItems->count(),
                        'status' => $order->status,
                        'payment_status' => $order->payment_status,
                        'created_at' => $order->created_at,
                        'completed_at' => $order->completed_at,
                    ]
                ], 201);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process order',
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