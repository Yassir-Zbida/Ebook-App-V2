<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EbookCategory;
use App\Models\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $query = EbookCategory::with(['ebook', 'children' => function($query) {
            $query->orderBy('sort_order');
        }])->whereNull('parent_id')->where('is_active', true);

        // Apply filters
        if ($request->has('ebook_id')) {
            $query->where('ebook_id', $request->ebook_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 20);
        $categories = $query->paginate($perPage);

        $categories->getCollection()->transform(function ($category) {
            return $this->transformCategory($category);
        });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Display the specified category
     */
    public function show(EbookCategory $category)
    {
        if (!$category->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $category->load(['ebook', 'children' => function($query) {
            $query->orderBy('sort_order');
        }]);

        return response()->json([
            'success' => true,
            'data' => $this->transformCategory($category)
        ]);
    }

    /**
     * Get resources for a specific category
     */
    public function resources(EbookCategory $category)
    {
        if (!$category->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
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
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description,
                    'ebook_id' => $category->ebook_id,
                ],
                'resources' => $transformedResources
            ]
        ]);
    }

    /**
     * Search categories
     */
    public function search(Request $request)
    {
        $validator = $request->validate([
            'q' => 'required|string|min:2',
            'per_page' => 'integer|min:1|max:50'
        ]);

        $query = EbookCategory::with(['ebook', 'children' => function($query) {
            $query->orderBy('sort_order');
        }])->where('is_active', true);

        $searchTerm = $request->q;
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
              ->orWhere('description', 'like', '%' . $searchTerm . '%');
        });

        $perPage = $request->get('per_page', 20);
        $categories = $query->paginate($perPage);

        $categories->getCollection()->transform(function ($category) {
            return $this->transformCategory($category);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'search_term' => $searchTerm,
                'results' => $categories
            ]
        ]);
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
            'parent_id' => $category->parent_id,
            'ebook_id' => $category->ebook_id,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
        ];

        if ($category->ebook) {
            $data['ebook'] = [
                'id' => $category->ebook->id,
                'title' => $category->ebook->title,
                'price' => $category->ebook->price,
                'cover_image' => $category->ebook->cover_image ? Storage::url($category->ebook->cover_image) : null,
            ];
        }

        if ($category->children->count() > 0) {
            $data['children'] = $category->children->map(function ($child) {
                return $this->transformCategory($child);
            });
        }

        return $data;
    }
} 