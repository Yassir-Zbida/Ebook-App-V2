<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EbookResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Ebook;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Get user's cart
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get or create cart for user
        $cart = Cart::where('session_id', $user->id)->first();
        
        if (!$cart) {
            return response()->json([
                'success' => true,
                'data' => [
                    'cart_items' => [],
                    'subtotal' => 0,
                    'discount_amount' => 0,
                    'tax_amount' => 0,
                    'total' => 0,
                    'items_count' => 0,
                    'coupon_code' => null,
                    'coupon_discount' => 0,
                ]
            ]);
        }

        $cartItems = $cart->items()->with('ebook')->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'cart_items' => $cartItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'ebook' => new EbookResource($item->ebook),
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'discount_amount' => $item->discount_amount,
                        'subtotal' => $item->subtotal,
                    ];
                }),
                'subtotal' => $cart->subtotal,
                'discount_amount' => $cart->discount_amount,
                'tax_amount' => $cart->tax_amount,
                'total' => $cart->total,
                'items_count' => $cart->items_count,
                'coupon_code' => $cart->coupon_code,
                'coupon_discount' => $cart->coupon_discount,
            ]
        ]);
    }

    /**
     * Add item to cart
     */
    public function addItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ebook_id' => 'required|exists:ebooks,id',
            'quantity' => 'integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $ebookId = $request->ebook_id;
        $quantity = $request->get('quantity', 1);

        // Check if ebook exists and is active
        $ebook = Ebook::where('id', $ebookId)->where('is_active', true)->first();
        if (!$ebook) {
            return response()->json([
                'success' => false,
                'message' => 'Ebook not found or not available'
            ], 404);
        }

        // Check if user already purchased this ebook
        if ($user->hasPurchased($ebookId)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already purchased this ebook'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Get or create cart
            $cart = Cart::where('session_id', $user->id)->first();
            if (!$cart) {
                $cart = Cart::create([
                    'session_id' => $user->id,
                    'expires_at' => now()->addDays(30),
                ]);
            }

            // Check if item already exists in cart
            $existingItem = $cart->items()->where('ebook_id', $ebookId)->first();
            
            if ($existingItem) {
                // Update quantity
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $quantity
                ]);
                $existingItem->calculateSubtotal();
            } else {
                // Create new cart item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'ebook_id' => $ebookId,
                    'quantity' => $quantity,
                    'price' => $ebook->price,
                    'discount_amount' => 0,
                    'subtotal' => $ebook->price * $quantity,
                ]);
            }

            // Recalculate cart totals
            $cart->calculateTotals();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart'
            ], 500);
        }
    }

    /**
     * Update cart item
     */
    public function updateItem(Request $request, $itemId)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $cart = Cart::where('session_id', $user->id)->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $cartItem = $cart->items()->where('id', $itemId)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        try {
            $cartItem->update(['quantity' => $request->quantity]);
            $cartItem->calculateSubtotal();
            $cart->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Cart item updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart item'
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request, $itemId)
    {
        $user = $request->user();
        $cart = Cart::where('session_id', $user->id)->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $cartItem = $cart->items()->where('id', $itemId)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }

        try {
            $cartItem->delete();
            $cart->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart'
            ], 500);
        }
    }

    /**
     * Clear cart
     */
    public function clear(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('session_id', $user->id)->first();

        if (!$cart) {
            return response()->json([
                'success' => true,
                'message' => 'Cart is already empty'
            ]);
        }

        try {
            $cart->clear();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart'
            ], 500);
        }
    }

    /**
     * Apply coupon to cart
     */
    public function applyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $cart = Cart::where('session_id', $user->id)->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code'
            ], 404);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon is not valid'
            ], 400);
        }

        if (!$coupon->canBeUsedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot use this coupon'
            ], 400);
        }

        if ($cart->subtotal < $coupon->minimum_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount not met for this coupon'
            ], 400);
        }

        try {
            $discountAmount = $coupon->calculateDiscount($cart->subtotal);
            
            $cart->update([
                'coupon_code' => $coupon->code,
                'coupon_discount' => $discountAmount,
                'discount_amount' => $discountAmount,
            ]);
            
            $cart->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully',
                'data' => [
                    'coupon_code' => $coupon->code,
                    'discount_amount' => $discountAmount,
                    'total' => $cart->total,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to apply coupon'
            ], 500);
        }
    }

    /**
     * Remove coupon from cart
     */
    public function removeCoupon(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('session_id', $user->id)->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        try {
            $cart->update([
                'coupon_code' => null,
                'coupon_discount' => 0,
                'discount_amount' => 0,
            ]);
            
            $cart->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Coupon removed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove coupon'
            ], 500);
        }
    }
} 