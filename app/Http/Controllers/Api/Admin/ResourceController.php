<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    /**
     * Display a listing of resources
     */
    public function index(Request $request)
    {
        $query = CategoryResource::with(['category.ebook']);

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

        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
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
     * Store a newly created resource
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:ebook_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'required|in:pdf,excel,xlsx,image,docx,pptx,table,supplier_info,product_data,text_content,form',
            'content_data' => 'nullable|json',
            'file' => 'nullable|file|mimes:pdf,xlsx,xls,docx,pptx,jpg,jpeg,png,gif|max:20480',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $resourceData = [
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->description,
                'content_type' => $request->content_type,
                'content_data' => $request->content_data,
                'sort_order' => $request->get('sort_order', 0),
                'is_active' => $request->get('is_active', true),
            ];

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('ebooks/resources', 'public');
                
                $resourceData['file_path'] = $path;
                $resourceData['original_filename'] = $file->getClientOriginalName();
                $resourceData['file_size'] = $file->getSize();
                $resourceData['mime_type'] = $file->getMimeType();
            }

            $resource = CategoryResource::create($resourceData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Resource created successfully',
                'data' => $this->transformResource($resource->fresh())
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create resource',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource
     */
    public function show(CategoryResource $resource)
    {
        $resource->load(['category.ebook']);

        return response()->json([
            'success' => true,
            'data' => $this->transformResource($resource)
        ]);
    }

    /**
     * Update the specified resource
     */
    public function update(Request $request, CategoryResource $resource)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'content_type' => 'sometimes|required|in:pdf,excel,xlsx,image,docx,pptx,table,supplier_info,product_data,text_content,form',
            'content_data' => 'nullable|json',
            'file' => 'nullable|file|mimes:pdf,xlsx,xls,docx,pptx,jpg,jpeg,png,gif|max:20480',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $updateData = $request->only(['title', 'description', 'content_type', 'content_data', 'sort_order', 'is_active']);

            // Handle file upload
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($resource->file_path) {
                    Storage::disk('public')->delete($resource->file_path);
                }

                $file = $request->file('file');
                $path = $file->store('ebooks/resources', 'public');
                
                $updateData['file_path'] = $path;
                $updateData['original_filename'] = $file->getClientOriginalName();
                $updateData['file_size'] = $file->getSize();
                $updateData['mime_type'] = $file->getMimeType();
            }

            $resource->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Resource updated successfully',
                'data' => $this->transformResource($resource->fresh())
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update resource',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource
     */
    public function destroy(CategoryResource $resource)
    {
        try {
            DB::beginTransaction();

            // Delete file if exists
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }

            $resource->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Resource deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete resource',
                'error' => $e->getMessage()
            ], 500);
        }
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