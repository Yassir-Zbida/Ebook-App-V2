<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\EbookCategory;
use App\Models\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $query = EbookCategory::with(['ebook', 'children' => function($query) {
            $query->orderBy('sort_order');
        }]);

        // Apply filters
        if ($request->has('ebook_id')) {
            $query->where('ebook_id', $request->ebook_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
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
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ebook_id' => 'required|exists:ebooks,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:ebook_categories,id',
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

            $categoryData = [
                'ebook_id' => $request->ebook_id,
                'name' => $request->name,
                'description' => $request->description,
                'icon' => $request->icon,
                'parent_id' => $request->parent_id,
                'sort_order' => $request->get('sort_order', 0),
                'is_active' => $request->get('is_active', true),
            ];

            $category = EbookCategory::create($categoryData);

            // Handle category image upload
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $path = $imageFile->store('ebooks/categories', 'public');
                $category->update(['image' => $path]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $this->transformCategory($category->fresh())
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified category
     */
    public function show(EbookCategory $category)
    {
        $category->load(['ebook', 'children' => function($query) {
            $query->orderBy('sort_order');
        }, 'resources']);

        return response()->json([
            'success' => true,
            'data' => $this->transformCategory($category)
        ]);
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, EbookCategory $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:ebook_categories,id',
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

            $updateData = $request->only(['name', 'description', 'icon', 'parent_id', 'sort_order', 'is_active']);

            // Handle category image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }

                $imageFile = $request->file('image');
                $path = $imageFile->store('ebooks/categories', 'public');
                $updateData['image'] = $path;
            }

            $category->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $this->transformCategory($category->fresh())
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified category
     */
    public function destroy(EbookCategory $category)
    {
        try {
            DB::beginTransaction();

            // Delete category image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add resource to category
     */
    public function addResource(Request $request, EbookCategory $category)
    {
        $validator = Validator::make($request->all(), [
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
                'category_id' => $category->id,
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
                'message' => 'Resource added successfully',
                'data' => [
                    'id' => $resource->id,
                    'title' => $resource->title,
                    'description' => $resource->description,
                    'content_type' => $resource->content_type,
                    'file_path' => $resource->file_path ? Storage::url($resource->file_path) : null,
                    'created_at' => $resource->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add resource',
                'error' => $e->getMessage()
            ], 500);
        }
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
            ];
        }

        if ($category->children->count() > 0) {
            $data['children'] = $category->children->map(function ($child) {
                return $this->transformCategory($child);
            });
        }

        if ($category->resources) {
            $data['resources_count'] = $category->resources->count();
        }

        return $data;
    }
} 