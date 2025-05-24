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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.description' => 'nullable|string',
            'categories.*.icon' => 'nullable|string|max:50',
            'categories.*.subcategories' => 'nullable|array',
            'categories.*.subcategories.*.name' => 'required|string|max:255',
            'categories.*.subcategories.*.description' => 'nullable|string',
            'categories.*.subcategories.*.icon' => 'nullable|string|max:50',
            'categories.*.subcategories.*.resources' => 'nullable|array',
            'categories.*.subcategories.*.resources.*.title' => 'required|string|max:255',
            'categories.*.subcategories.*.resources.*.content_type' => 'required|in:pdf,image,table',
            'categories.*.subcategories.*.resources.*.file' => 'required_if:categories.*.subcategories.*.resources.*.content_type,pdf,image|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'categories.*.subcategories.*.resources.*.table_data' => 'required_if:categories.*.subcategories.*.resources.*.content_type,table|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

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

        // Create categories and their relationships
        foreach ($request->categories as $categoryData) {
            $category = $ebook->categories()->create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'] ?? null,
                'icon' => $categoryData['icon'] ?? null,
                'sort_order' => 0,
                'is_active' => true,
            ]);

            // Create subcategories if any
            if (isset($categoryData['subcategories'])) {
                foreach ($categoryData['subcategories'] as $subcategoryData) {
                    $subcategory = $category->children()->create([
                        'ebook_id' => $ebook->id,
                        'name' => $subcategoryData['name'],
                        'description' => $subcategoryData['description'] ?? null,
                        'icon' => $subcategoryData['icon'] ?? null,
                        'sort_order' => 0,
                        'is_active' => true,
                    ]);

                    // Create resources if any
                    if (isset($subcategoryData['resources'])) {
                        foreach ($subcategoryData['resources'] as $resourceData) {
                            $resource = $subcategory->resources()->create([
                                'title' => $resourceData['title'],
                                'content_type' => $resourceData['content_type'],
                                'description' => $resourceData['description'] ?? null,
                                'sort_order' => 0,
                                'is_active' => true,
                            ]);

                            // Handle resource content based on type
                            if ($resourceData['content_type'] === 'table') {
                                $resource->update([
                                    'content_data' => $resourceData['table_data']
                                ]);
                            } elseif (isset($resourceData['file'])) {
                                $path = $resourceData['file']->store('ebooks/resources', 'public');
                                $resource->update(['file_path' => $path]);
                            }
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.ebooks.index')
            ->with('success', 'Ebook created successfully.');
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