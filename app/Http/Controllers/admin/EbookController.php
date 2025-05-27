<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\EbookCategory;
use App\Models\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
        // Debug the incoming request
        \Log::info('Ebook creation request:', $request->all());

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.description' => 'nullable|string',
            'categories.*.icon' => 'nullable|string|max:50',
            'categories.*.parent_index' => 'nullable|string',
            'categories.*.level' => 'nullable|integer|min:0',
            'categories.*.resource.title' => 'nullable|string|max:255',
            'categories.*.resource.content_type' => 'nullable|in:pdf,excel,xlsx',
            'categories.*.resource.description' => 'nullable|string',
            'categories.*.resource.file' => 'nullable|file|mimes:pdf,xlsx,xls|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Create ebook
            $ebook = Ebook::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'is_active' => true,
            ]);

            // Handle cover image
            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('ebooks/covers', 'public');
                $ebook->update(['cover_image' => $path]);
            }

            // Process categories from flat structure
            $this->processFlatCategories($request->categories, $ebook->id, $request);

            return redirect()->route('admin.ebooks.index')
                ->with('success', 'Ebook created successfully.');

        } catch (\Exception $e) {
            \Log::error('Error creating ebook:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'An error occurred while creating the ebook: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Process flat categories structure from the form
     */
    private function processFlatCategories($categories, $ebookId, $request)
    {
        $createdCategories = [];
        
        // First pass: create all categories without parent relationships
        foreach ($categories as $index => $categoryData) {
            $category = EbookCategory::create([
                'ebook_id' => $ebookId,
                'parent_id' => null, // Will be set in second pass
                'name' => $categoryData['name'],
                'description' => $categoryData['description'] ?? null,
                'icon' => $categoryData['icon'] ?? null,
                'sort_order' => $index,
                'is_active' => true,
            ]);

            $createdCategories[$index] = [
                'category' => $category,
                'parent_index' => $categoryData['parent_index'] ?? null,
                'level' => $categoryData['level'] ?? 0
            ];

            \Log::info('Created category:', [
                'id' => $category->id, 
                'name' => $category->name, 
                'index' => $index,
                'parent_index' => $categoryData['parent_index'] ?? null
            ]);
        }

        // Second pass: set parent relationships
        foreach ($createdCategories as $index => $categoryInfo) {
            $parentIndex = $categoryInfo['parent_index'];
            if ($parentIndex !== null && $parentIndex !== '' && isset($createdCategories[$parentIndex])) {
                $parentCategory = $createdCategories[$parentIndex]['category'];
                $categoryInfo['category']->update(['parent_id' => $parentCategory->id]);
                
                \Log::info('Set parent relationship:', [
                    'child_id' => $categoryInfo['category']->id,
                    'parent_id' => $parentCategory->id
                ]);
            }
        }

        // Third pass: handle resources
        foreach ($categories as $index => $categoryData) {
            if (isset($categoryData['resource']) && !empty($categoryData['resource']['title'])) {
                $resourceData = $categoryData['resource'];
                $category = $createdCategories[$index]['category'];
                
                $resource = CategoryResource::create([
                    'category_id' => $category->id,
                    'title' => $resourceData['title'],
                    'content_type' => $resourceData['content_type'],
                    'description' => $resourceData['description'] ?? null,
                    'sort_order' => 0,
                    'is_active' => true,
                ]);

                // Handle file upload
                if ($request->hasFile("categories.{$index}.resource.file")) {
                    $file = $request->file("categories.{$index}.resource.file");
                    $path = $file->store('ebooks/resources', 'public');
                    $resource->update(['file_path' => $path]);
                    
                    \Log::info('Uploaded resource file:', ['resource_id' => $resource->id, 'path' => $path]);
                }
            }
        }
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

        // Delete all resources and their files
        foreach ($ebook->categories as $category) {
            foreach ($category->children as $subcategory) {
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