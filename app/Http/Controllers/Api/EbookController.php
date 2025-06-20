<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    /**
     * Display a listing of ebooks
     */
    public function index(Request $request)
    {
        $query = Ebook::with(['categories' => function($query) {
            $query->whereNull('parent_id')->orderBy('sort_order');
        }])->where('is_active', true);

        // Apply filters
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 12);
        $ebooks = $query->paginate($perPage);

        // Transform data for API response
        $ebooks->getCollection()->transform(function ($ebook) {
            return [
                'id' => $ebook->id,
                'title' => $ebook->title,
                'description' => $ebook->description,
                'price' => $ebook->price,
                'cover_image' => $ebook->cover_image ? Storage::url($ebook->cover_image) : null,
                'categories_count' => $ebook->categories->count(),
                'resources_count' => $ebook->getAllResourcesCount(),
                'created_at' => $ebook->created_at,
                'updated_at' => $ebook->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $ebooks
        ]);
    }

    /**
     * Display the specified ebook
     */
    public function show(Ebook $ebook)
    {
        if (!$ebook->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ebook not found'
            ], 404);
        }

        $ebook->load(['categories' => function($query) {
            $query->whereNull('parent_id')->orderBy('sort_order');
        }]);

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
                'created_at' => $ebook->created_at,
                'updated_at' => $ebook->updated_at,
            ]
        ]);
    }

    /**
     * Get categories for a specific ebook
     */
    public function categories(Ebook $ebook)
    {
        if (!$ebook->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ebook not found'
            ], 404);
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
     * Search ebooks
     */
    public function search(Request $request)
    {
        $validator = $request->validate([
            'q' => 'required|string|min:2',
            'per_page' => 'integer|min:1|max:50'
        ]);

        $query = Ebook::with(['categories' => function($query) {
            $query->whereNull('parent_id')->orderBy('sort_order');
        }])->where('is_active', true);

        $searchTerm = $request->q;
        $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', '%' . $searchTerm . '%')
              ->orWhere('description', 'like', '%' . $searchTerm . '%');
        });

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
                'created_at' => $ebook->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'search_term' => $searchTerm,
                'results' => $ebooks
            ]
        ]);
    }

    /**
     * Get featured ebooks
     */
    public function featured()
    {
        $ebooks = Ebook::with(['categories' => function($query) {
            $query->whereNull('parent_id')->orderBy('sort_order');
        }])
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->limit(6)
        ->get();

        $ebooks->transform(function ($ebook) {
            return [
                'id' => $ebook->id,
                'title' => $ebook->title,
                'description' => $ebook->description,
                'price' => $ebook->price,
                'cover_image' => $ebook->cover_image ? Storage::url($ebook->cover_image) : null,
                'categories_count' => $ebook->categories->count(),
                'resources_count' => $ebook->getAllResourcesCount(),
                'created_at' => $ebook->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $ebooks
        ]);
    }

    /**
     * Get popular ebooks (based on purchase count)
     */
    public function popular()
    {
        $ebooks = Ebook::with(['categories' => function($query) {
            $query->whereNull('parent_id')->orderBy('sort_order');
        }])
        ->withCount('purchasers')
        ->where('is_active', true)
        ->orderBy('purchasers_count', 'desc')
        ->limit(6)
        ->get();

        $ebooks->transform(function ($ebook) {
            return [
                'id' => $ebook->id,
                'title' => $ebook->title,
                'description' => $ebook->description,
                'price' => $ebook->price,
                'cover_image' => $ebook->cover_image ? Storage::url($ebook->cover_image) : null,
                'categories_count' => $ebook->categories->count(),
                'resources_count' => $ebook->getAllResourcesCount(),
                'purchasers_count' => $ebook->purchasers_count,
                'created_at' => $ebook->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $ebooks
        ]);
    }

    /**
     * Get reviews for an ebook
     */
    public function reviews(Ebook $ebook)
    {
        // This would be implemented when you add a reviews table
        return response()->json([
            'success' => true,
            'data' => [
                'ebook_id' => $ebook->id,
                'reviews' => [],
                'message' => 'Reviews feature coming soon'
            ]
        ]);
    }

    /**
     * Store a review for an ebook
     */
    public function storeReview(Request $request, Ebook $ebook)
    {
        // This would be implemented when you add a reviews table
        return response()->json([
            'success' => false,
            'message' => 'Reviews feature coming soon'
        ], 501);
    }

    /**
     * Update a review
     */
    public function updateReview(Request $request, Ebook $ebook, $reviewId)
    {
        // This would be implemented when you add a reviews table
        return response()->json([
            'success' => false,
            'message' => 'Reviews feature coming soon'
        ], 501);
    }

    /**
     * Delete a review
     */
    public function deleteReview(Ebook $ebook, $reviewId)
    {
        // This would be implemented when you add a reviews table
        return response()->json([
            'success' => false,
            'message' => 'Reviews feature coming soon'
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