<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    /**
     * Display a listing of resources
     */
    public function index(Request $request)
    {
        $query = CategoryResource::with(['category.ebook'])->where('is_active', true);

        // Apply filters
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('content_type')) {
            $query->where('content_type', $request->content_type);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 20);
        $resources = $query->paginate($perPage);

        $resources->getCollection()->transform(function ($resource) {
            return $this->transformResource($resource);
        });

        return response()->json([
            'success' => true,
            'data' => $resources
        ]);
    }

    /**
     * Display the specified resource
     */
    public function show(CategoryResource $resource)
    {
        if (!$resource->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ], 404);
        }

        $resource->load(['category.ebook']);

        return response()->json([
            'success' => true,
            'data' => $this->transformResource($resource)
        ]);
    }

    /**
     * Transform resource data for API response
     */
    private function transformResource($resource)
    {
        $data = [
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
            'is_active' => $resource->is_active,
            'category_id' => $resource->category_id,
            'created_at' => $resource->created_at,
            'updated_at' => $resource->updated_at,
        ];

        if ($resource->category) {
            $data['category'] = [
                'id' => $resource->category->id,
                'name' => $resource->category->name,
                'description' => $resource->category->description,
            ];

            if ($resource->category->ebook) {
                $data['category']['ebook'] = [
                    'id' => $resource->category->ebook->id,
                    'title' => $resource->category->ebook->title,
                    'price' => $resource->category->ebook->price,
                ];
            }
        }

        return $data;
    }
} 