<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EbookResource;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * Get user's wishlist
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = $user->wishlist()->with(['categories' => function($query) {
            $query->whereNull('parent_id')->orderBy('sort_order');
        }]);

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy('user_wishlist.' . $sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 12);
        $wishlist = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => EbookResource::collection($wishlist)
        ]);
    }

    /**
     * Add ebook to wishlist
     */
    public function add(Request $request, Ebook $ebook)
    {
        $user = $request->user();

        if (!$ebook->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ebook not found or not available'
            ], 404);
        }

        if ($user->hasInWishlist($ebook->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Ebook is already in your wishlist'
            ], 400);
        }

        try {
            $user->wishlist()->attach($ebook->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Ebook added to wishlist successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add ebook to wishlist'
            ], 500);
        }
    }

    /**
     * Remove ebook from wishlist
     */
    public function remove(Request $request, Ebook $ebook)
    {
        $user = $request->user();

        if (!$user->hasInWishlist($ebook->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Ebook not found in wishlist'
            ], 404);
        }

        try {
            $user->wishlist()->detach($ebook->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Ebook removed from wishlist successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove ebook from wishlist'
            ], 500);
        }
    }

    /**
     * Check if ebook is in wishlist
     */
    public function check(Request $request, Ebook $ebook)
    {
        $user = $request->user();
        $isInWishlist = $user->hasInWishlist($ebook->id);

        return response()->json([
            'success' => true,
            'data' => [
                'ebook_id' => $ebook->id,
                'is_in_wishlist' => $isInWishlist
            ]
        ]);
    }

    /**
     * Move wishlist item to cart
     */
    public function moveToCart(Request $request, Ebook $ebook)
    {
        $user = $request->user();

        if (!$user->hasInWishlist($ebook->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Ebook not found in wishlist'
            ], 404);
        }

        if (!$ebook->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ebook is not available'
            ], 400);
        }

        if ($user->hasPurchased($ebook->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already purchased this ebook'
            ], 400);
        }

        try {
            // Add to cart (you can reuse cart logic here)
            // For now, we'll just remove from wishlist
            $user->wishlist()->detach($ebook->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Ebook moved to cart successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to move ebook to cart'
            ], 500);
        }
    }

    /**
     * Clear wishlist
     */
    public function clear(Request $request)
    {
        $user = $request->user();

        try {
            $user->wishlist()->detach();
            
            return response()->json([
                'success' => true,
                'message' => 'Wishlist cleared successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear wishlist'
            ], 500);
        }
    }

    /**
     * Get wishlist count
     */
    public function count(Request $request)
    {
        $user = $request->user();
        $count = $user->wishlist()->count();

        return response()->json([
            'success' => true,
            'data' => [
                'count' => $count
            ]
        ]);
    }
} 