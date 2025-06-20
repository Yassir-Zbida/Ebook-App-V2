<?php

namespace App\Http\Controllers\Admin;

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
    public function index()
    {
        $ebooks = Ebook::with(['categories' => function($query) {
            $query->whereNull('parent_id');
        }])->latest()->get();
        
        return view('admin.ebooks.index', compact('ebooks'));
    }

    /**
     * Show the form for creating a new ebook
     */
    public function create()
    {
        return view('admin.ebooks.create');
    }

    /**
     * Store a newly created ebook
     */
    public function store(Request $request)
    {
        \Log::info('=== EBOOK CREATION STARTED ===');
        
        try {
            // Log basic request info safely
            \Log::info('Title: ' . $request->input('title'));
            \Log::info('Price: ' . $request->input('price'));
            \Log::info('Has cover image: ' . ($request->hasFile('cover_image') ? 'yes' : 'no'));
            \Log::info('Method: ' . $request->method());

            $categories = $request->input('categories', []);
            \Log::info('Categories type: ' . gettype($categories));
            \Log::info('Categories count: ' . (is_array($categories) ? count($categories) : 'N/A'));

            // Debug the categories structure
            if (is_array($categories) && !empty($categories)) {
                $firstCategory = reset($categories);
                \Log::info('First category type: ' . gettype($firstCategory));
                \Log::info('First category is string: ' . (is_string($firstCategory) ? 'yes' : 'no'));
                \Log::info('First category is array: ' . (is_array($firstCategory) ? 'yes' : 'no'));
                
                if (is_string($firstCategory)) {
                    \Log::info('First category value: ' . substr($firstCategory, 0, 50));
                }
                
                if (is_array($firstCategory)) {
                    \Log::info('First category keys: ' . implode(', ', array_keys($firstCategory)));
                }
            }

            // Check if we received categories as expected array structure
            if (empty($categories) || !is_array($categories)) {
                \Log::error('Categories validation failed: Invalid structure');
                return back()->withErrors(['categories' => 'Invalid category data structure received.'])->withInput();
            }
            
            // Check if first category is a string (wrong format) instead of array
            $firstCategory = reset($categories);
            if (is_string($firstCategory)) {
                \Log::error('Categories validation failed: Received as strings instead of objects');
                return back()->withErrors(['categories' => 'Category data format is incorrect. Form data was not structured properly.'])->withInput();
            }
            
            // Filter out empty categories
            $filteredCategories = array_filter($categories, function($category) {
                return is_array($category) && !empty($category['name']);
            });
            
            // Re-index the array to avoid gaps
            $filteredCategories = array_values($filteredCategories);
            \Log::info('Categories after filtering: ' . count($filteredCategories));
            
            // Update the request with filtered categories
            $request->merge(['categories' => $filteredCategories]);

            // Check if we have any categories after filtering
            if (empty($filteredCategories)) {
                \Log::warning('No valid categories found after filtering');
                return back()->withErrors(['categories' => 'At least one category with a name is required.'])->withInput();
            }

            // Validation
            \Log::info('Starting validation...');
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'categories' => 'required|array|min:1',
                'categories.*.name' => 'required|string|max:255',
                'categories.*.description' => 'nullable|string',
                'categories.*.icon' => 'nullable|string|max:50',
                'categories.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'categories.*.parent_index' => 'nullable|string',
                'categories.*.level' => 'nullable|integer|min:0',
                'categories.*.resource.title' => 'nullable|string|max:255',
                'categories.*.resource.content_type' => 'nullable|in:pdf,excel,xlsx',
                'categories.*.resource.description' => 'nullable|string',
                'categories.*.resource.file' => 'nullable|file|mimes:pdf,xlsx,xls|max:20480', // 20MB max
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed');
                return back()->withErrors($validator)->withInput();
            }
            
            \Log::info('Validation passed successfully');

        } catch (\Exception $e) {
            \Log::error('Error in validation phase: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Validation error: ' . $e->getMessage()])->withInput();
        }

        try {
            \Log::info('Starting database transaction...');
            \DB::beginTransaction();
            
            // Create ebook
            $ebookData = [
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'is_active' => true,
            ];
            
            $ebook = Ebook::create($ebookData);
            \Log::info('Ebook created with ID: ' . $ebook->id);

            // Handle cover image
            if ($request->hasFile('cover_image')) {
                \Log::info('Processing cover image...');
                $coverFile = $request->file('cover_image');
                $path = $coverFile->store('ebooks/covers', 'public');
                $ebook->update(['cover_image' => $path]);
                \Log::info('Cover image uploaded to: ' . $path);
            }

            // Process categories
            \Log::info('Processing categories...');
            $this->processFlatCategories($filteredCategories, $ebook->id, $request);
            \Log::info('Categories processed successfully');

            \DB::commit();
            \Log::info('=== EBOOK CREATION COMPLETED SUCCESSFULLY ===');

            return redirect()->route('admin.ebooks.index')
                ->with('success', 'Ebook "' . $ebook->title . '" created successfully with ' . count($filteredCategories) . ' categories!');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Database error: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Failed to create ebook: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Process flat categories structure from the form
     */
    private function processFlatCategories($categories, $ebookId, $request)
    {
        \Log::info('=== PROCESSING CATEGORIES ===');
        \Log::info('Categories to process: ' . count($categories));
        \Log::info('Ebook ID: ' . $ebookId);
        
        $createdCategories = [];
        
        try {
            // First pass: create all categories without parent relationships
            \Log::info('Phase 1: Creating categories...');
            foreach ($categories as $index => $categoryData) {
                \Log::info('Creating category ' . $index . ': ' . ($categoryData['name'] ?? 'unnamed'));
                
                // Handle category image upload
                $categoryImagePath = null;
                
                if ($request->hasFile("categories.{$index}.image")) {
                    \Log::info('Processing image for category ' . $index);
                    $imageFile = $request->file("categories.{$index}.image");
                    $imageName = time() . '_cat_' . $index . '_' . str_replace(' ', '_', $imageFile->getClientOriginalName());
                    $categoryImagePath = $imageFile->storeAs('ebooks/categories', $imageName, 'public');
                    \Log::info('Category image uploaded: ' . $categoryImagePath);
                }

                $categoryCreateData = [
                    'ebook_id' => $ebookId,
                    'parent_id' => null, // Will be set in second pass
                    'name' => $categoryData['name'],
                    'description' => $categoryData['description'] ?? null,
                    'icon' => $categoryData['icon'] ?? null,
                    'image' => $categoryImagePath,
                    'sort_order' => $index,
                    'is_active' => true,
                ];
                
                $category = EbookCategory::create($categoryCreateData);
                \Log::info('Category created with ID: ' . $category->id);

                $createdCategories[$index] = [
                    'category' => $category,
                    'parent_index' => $categoryData['parent_index'] ?? null,
                    'level' => $categoryData['level'] ?? 0
                ];
            }
            \Log::info('Phase 1 completed: ' . count($createdCategories) . ' categories created');
            
        } catch (\Exception $e) {
            \Log::error('Error in Phase 1 (creating categories): ' . $e->getMessage());
            throw $e;
        }

        try {
            // Second pass: set parent relationships
            \Log::info('Phase 2: Setting parent relationships...');
            $parentRelationshipsSet = 0;
            foreach ($createdCategories as $index => $categoryInfo) {
                $parentIndex = $categoryInfo['parent_index'];
                
                if ($parentIndex !== null && $parentIndex !== '' && isset($createdCategories[$parentIndex])) {
                    $parentCategory = $createdCategories[$parentIndex]['category'];
                    \Log::info('Setting parent for category ' . $index . ' (ID: ' . $categoryInfo['category']->id . ') to category ' . $parentIndex . ' (ID: ' . $parentCategory->id . ')');
                    $categoryInfo['category']->update(['parent_id' => $parentCategory->id]);
                    $parentRelationshipsSet++;
                }
            }
            \Log::info('Phase 2 completed: ' . $parentRelationshipsSet . ' parent relationships set');
            
        } catch (\Exception $e) {
            \Log::error('Error in Phase 2 (setting parent relationships): ' . $e->getMessage());
            throw $e;
        }

        try {
            // Third pass: handle resources
            \Log::info('Phase 3: Creating resources...');
            $resourcesCreated = 0;
            foreach ($categories as $index => $categoryData) {
                if (isset($categoryData['resource']) && !empty($categoryData['resource']['title'])) {
                    \Log::info('Processing resource for category ' . $index . ': ' . $categoryData['resource']['title']);
                    $resourceData = $categoryData['resource'];
                    $category = $createdCategories[$index]['category'];
                    
                    // Handle file upload first
                    $filePath = null;
                    $originalName = null;
                    $fileSize = null;
                    $mimeType = null;
                    
                    if ($request->hasFile("categories.{$index}.resource.file")) {
                        \Log::info('Processing resource file for category ' . $index);
                        $file = $request->file("categories.{$index}.resource.file");
                        $originalName = $file->getClientOriginalName();
                        $fileSize = $file->getSize();
                        $mimeType = $file->getMimeType();
                        $fileName = time() . '_' . $index . '_' . str_replace(' ', '_', $originalName);
                        $filePath = $file->storeAs('ebooks/resources', $fileName, 'public');
                        \Log::info('Resource file uploaded: ' . $filePath . ' (Size: ' . round($fileSize/1024/1024, 2) . 'MB)');
                    }
                    
                    $resourceCreateData = [
                        'category_id' => $category->id,
                        'title' => $resourceData['title'],
                        'content_type' => $resourceData['content_type'],
                        'description' => $resourceData['description'] ?? null,
                        'file_path' => $filePath,
                        'original_filename' => $originalName,
                        'file_size' => $fileSize,
                        'mime_type' => $mimeType,
                        'sort_order' => 0,
                        'is_active' => true,
                    ];
                    
                    $resource = CategoryResource::create($resourceCreateData);
                    \Log::info('Resource created with ID: ' . $resource->id);
                    $resourcesCreated++;
                }
            }
            \Log::info('Phase 3 completed: ' . $resourcesCreated . ' resources created');
            
        } catch (\Exception $e) {
            \Log::error('Error in Phase 3 (creating resources): ' . $e->getMessage());
            throw $e;
        }
        
        \Log::info('=== CATEGORY PROCESSING COMPLETED ===');
        \Log::info('Summary: ' . count($createdCategories) . ' categories, ' . ($resourcesCreated ?? 0) . ' resources');
    }

    /**
     * Display the specified ebook
     */
    public function show(Ebook $ebook)
    {
        $ebook->load(['categories' => function($query) {
            $query->whereNull('parent_id')
                  ->with(['children' => function($query) {
                      $query->with('resources');
                  }]);
        }]);
        
        return view('admin.ebooks.show', compact('ebook'));
    }

    /**
     * Show the form for editing the specified ebook
     */
    public function edit(Ebook $ebook)
    {
        $ebook->load(['categories' => function($query) {
            $query->whereNull('parent_id')
                  ->with(['children' => function($query) {
                      $query->with('resources');
                  }]);
        }]);
        
        return view('admin.ebooks.edit', compact('ebook'));
    }

    /**
     * Update the specified ebook
     */
    public function update(Request $request, Ebook $ebook)
    {
        // Similar validation and update logic as store method
        // But with additional checks for existing records
        // Implementation will be added if needed
    }

    /**
     * Remove the specified ebook
     */
    public function destroy(Ebook $ebook)
    {
        // Delete associated files
        if ($ebook->cover_image) {
            Storage::disk('public')->delete($ebook->cover_image);
        }

        // Delete all resources and their files, and category images
        foreach ($ebook->categories as $category) {
            // Delete category image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            // Delete subcategory images and resources
            foreach ($category->children as $subcategory) {
                if ($subcategory->image) {
                    Storage::disk('public')->delete($subcategory->image);
                }
                
                foreach ($subcategory->resources as $resource) {
                    if ($resource->file_path) {
                        Storage::disk('public')->delete($resource->file_path);
                    }
                }
            }
        }

        $ebook->delete();

        return redirect()->route('admin.ebooks.index')
            ->with('success', 'Ebook deleted successfully.');
    }
} 