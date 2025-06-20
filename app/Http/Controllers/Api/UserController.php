<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($request->only(['name', 'email']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    /**
     * Get user's purchased ebooks
     */
    public function purchasedEbooks(Request $request)
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
        $ebooks = $query->paginate($perPage);

        $ebooks->getCollection()->transform(function ($ebook) {
            return [
                'id' => $ebook->id,
                'title' => $ebook->title,
                'description' => $ebook->description,
                'price' => $ebook->price,
                'cover_image' => $ebook->cover_image ? Storage::url($ebook->cover_image) : null,
                'categories_count' => $ebook->categories->count(),
                'resources_count' => $ebook->getAllResourcesCount(),
                'purchase_price' => $ebook->pivot->purchase_price,
                'purchased_at' => $ebook->pivot->purchased_at,
                'created_at' => $ebook->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $ebooks
        ]);
    }

    /**
     * Get detailed information about a purchased ebook
     */
    public function purchasedEbookDetail(Request $request, Ebook $ebook)
    {
        $user = $request->user();

        if (!$user->hasPurchased($ebook->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You have not purchased this ebook'
            ], 403);
        }

        $purchaseInfo = $user->purchasedEbooks()
            ->where('ebook_id', $ebook->id)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $ebook->id,
                'title' => $ebook->title,
                'description' => $ebook->description,
                'price' => $ebook->price,
                'cover_image' => $ebook->cover_image ? Storage::url($ebook->cover_image) : null,
                'categories_count' => $ebook->categories->count(),
                'resources_count' => $ebook->getAllResourcesCount(),
                'purchase_price' => $purchaseInfo->pivot->purchase_price,
                'purchased_at' => $purchaseInfo->pivot->purchased_at,
                'created_at' => $ebook->created_at,
                'updated_at' => $ebook->updated_at,
            ]
        ]);
    }

    /**
     * Get categories for a purchased ebook
     */
    public function purchasedEbookCategories(Request $request, Ebook $ebook)
    {
        $user = $request->user();

        if (!$user->hasPurchased($ebook->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You have not purchased this ebook'
            ], 403);
        }

        $categories = $ebook->rootCategories()->with(['children' => function($query) {
            $query->orderBy('sort_order');
        }])->get();

        $transformedCategories = $categories->map(function ($category) {
            return $this->transformCategory($category);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'ebook' => [
                    'id' => $ebook->id,
                    'title' => $ebook->title,
                ],
                'categories' => $transformedCategories
            ]
        ]);
    }

    /**
     * Get resources for a specific category in a purchased ebook
     */
    public function purchasedEbookResources(Request $request, Ebook $ebook, EbookCategory $category)
    {
        $user = $request->user();

        if (!$user->hasPurchased($ebook->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You have not purchased this ebook'
            ], 403);
        }

        // Check if category belongs to the ebook
        if ($category->ebook_id !== $ebook->id) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found in this ebook'
            ], 404);
        }

        $resources = CategoryResource::where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $transformedResources = $resources->map(function ($resource) {
            return [
                'id' => $resource->id,
                'title' => $resource->title,
                'description' => $resource->description,
                'content_type' => $resource->content_type,
                'content_data' => $resource->content_data,
                'file_path' => $resource->file_path ? Storage::url($resource->file_path) : null,
                'original_filename' => $resource->original_filename,
                'file_size' => $resource->file_size,
                'mime_type' => $resource->mime_type,
                'sort_order' => $resource->sort_order,
                'created_at' => $resource->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'ebook' => [
                    'id' => $ebook->id,
                    'title' => $ebook->title,
                ],
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                ],
                'resources' => $transformedResources
            ]
        ]);
    }

    /**
     * Get user's wishlist
     */
    public function wishlist(Request $request)
    {
        // This would be implemented when you add a wishlist table
        return response()->json([
            'success' => true,
            'data' => [
                'wishlist' => [],
                'message' => 'Wishlist feature coming soon'
            ]
        ]);
    }

    /**
     * Add ebook to wishlist
     */
    public function addToWishlist(Request $request, Ebook $ebook)
    {
        // This would be implemented when you add a wishlist table
        return response()->json([
            'success' => false,
            'message' => 'Wishlist feature coming soon'
        ], 501);
    }

    /**
     * Remove ebook from wishlist
     */
    public function removeFromWishlist(Request $request, Ebook $ebook)
    {
        // This would be implemented when you add a wishlist table
        return response()->json([
            'success' => false,
            'message' => 'Wishlist feature coming soon'
        ], 501);
    }

    /**
     * Transform category data for API response
     */
    private function transformCategory($category)
    {
        $data = [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'icon' => $category->icon,
            'image' => $category->image ? Storage::url($category->image) : null,
            'sort_order' => $category->sort_order,
            'is_active' => $category->is_active,
            'created_at' => $category->created_at,
        ];

        if ($category->children->count() > 0) {
            $data['children'] = $category->children->map(function ($child) {
                return $this->transformCategory($child);
            });
        }

        return $data;
    }
} 