<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EbookController extends Controller
{
    /**
     * Display a listing of ebooks
     */
    public function index(Request $request)
    {
        $query = Ebook::with(['categories' => function($query) {
            $query->whereNull('parent_id')->orderBy('sort_order');
        }]);

        // Apply filters
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
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

        $ebooks->getCollection()->transform(function ($ebook) {
            return [
                'id' => $ebook->id,
                'title' => $ebook->title,
                'description' => $ebook->description,
                'price' => $ebook->price,
                'cover_image' => $ebook->cover_image ? Storage::url($ebook->cover_image) : null,
                'is_active' => $ebook->is_active,
                'categories_count' => $ebook->categories->count(),
                'resources_count' => $ebook->getAllResourcesCount(),
                'purchasers_count' => $ebook->purchasers()->count(),
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
     * Store a newly created ebook
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            $ebookData = [
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'is_active' => $request->get('is_active', true),
            ];

            $ebook = Ebook::create($ebookData);

            // Handle cover image
            if ($request->hasFile('cover_image')) {
                $coverFile = $request->file('cover_image');
                $path = $coverFile->store('ebooks/covers', 'public');
                $ebook->update(['cover_image' => $path]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ebook created successfully',
                'data' => [
                    'id' => $ebook->id,
                    'title' => $ebook->title,
                    'description' => $ebook->description,
                    'price' => $ebook->price,
                    'cover_image' => $ebook->cover_image ? Storage::url($ebook->cover_image) : null,
                    'is_active' => $ebook->is_active,
                    'created_at' => $ebook->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create ebook',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified ebook
     */
    public function show(Ebook $ebook)
    {
        $ebook->load(['categories' => function($query) {
            $query->orderBy('sort_order');
        }, 'purchasers']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $ebook->id,
                'title' => $ebook->title,
                'description' => $ebook->description,
                'price' => $ebook->price,
                'cover_image' => $ebook->cover_image ? Storage::url($ebook->cover_image) : null,
                'is_active' => $ebook->is_active,
                'categories_count' => $ebook->categories->count(),
                'resources_count' => $ebook->getAllResourcesCount(),
                'purchasers_count' => $ebook->purchasers->count(),
                'categories' => $ebook->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'description' => $category->description,
                        'sort_order' => $category->sort_order,
                        'is_active' => $category->is_active,
                    ];
                }),
                'purchasers' => $ebook->purchasers->map(function ($purchaser) {
                    return [
                        'id' => $purchaser->id,
                        'name' => $purchaser->name,
                        'email' => $purchaser->email,
                        'purchase_price' => $purchaser->pivot->purchase_price,
                        'purchased_at' => $purchaser->pivot->purchased_at,
                    ];
                }),
                'created_at' => $ebook->created_at,
                'updated_at' => $ebook->updated_at,
            ]
        ]);
    }

    /**
     * Update the specified ebook
     */
    public function update(Request $request, Ebook $ebook)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

            $updateData = $request->only(['title', 'description', 'price', 'is_active']);

            // Handle cover image
            if ($request->hasFile('cover_image')) {
                // Delete old cover image if exists
                if ($ebook->cover_image) {
                    Storage::disk('public')->delete($ebook->cover_image);
                }

                $coverFile = $request->file('cover_image');
                $path = $coverFile->store('ebooks/covers', 'public');
                $updateData['cover_image'] = $path;
            }

            $ebook->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ebook updated successfully',
                'data' => [
                    'id' => $ebook->id,
                    'title' => $ebook->title,
                    'description' => $ebook->description,
                    'price' => $ebook->price,
                    'cover_image' => $ebook->cover_image ? Storage::url($ebook->cover_image) : null,
                    'is_active' => $ebook->is_active,
                    'updated_at' => $ebook->updated_at,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ebook',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified ebook
     */
    public function destroy(Ebook $ebook)
    {
        try {
            DB::beginTransaction();

            // Delete cover image if exists
            if ($ebook->cover_image) {
                Storage::disk('public')->delete($ebook->cover_image);
            }

            $ebook->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ebook deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete ebook',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publish an ebook
     */
    public function publish(Ebook $ebook)
    {
        $ebook->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Ebook published successfully',
            'data' => [
                'id' => $ebook->id,
                'title' => $ebook->title,
                'is_active' => $ebook->is_active,
            ]
        ]);
    }

    /**
     * Unpublish an ebook
     */
    public function unpublish(Ebook $ebook)
    {
        $ebook->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Ebook unpublished successfully',
            'data' => [
                'id' => $ebook->id,
                'title' => $ebook->title,
                'is_active' => $ebook->is_active,
            ]
        ]);
    }
} 