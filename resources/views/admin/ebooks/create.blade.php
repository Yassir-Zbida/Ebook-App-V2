@extends('layouts.app')

@section('title', 'Create New Ebook')
@section('page-title', 'Create New Ebook')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-primary to-primary-light p-6 rounded-2xl shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i class="ri-book-add-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-white mb-1">Create New Ebook</h1>
                    <p class="text-purple-100">Add a new ebook to your catalog with categories and resources</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data" id="ebookForm">
        @csrf
        
        <!-- Basic Ebook Information -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm p-8 card-shadow">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-information-line text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Basic Information</h3>
                    <p class="text-gray-600 dark:text-gray-light text-sm">Essential details about your ebook</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="space-y-2">
                    <label for="title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Title</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-book-line text-gray-400"></i>
                        </div>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                               class="form-input pl-10 @error('title') border-red-500 @enderror" 
                               placeholder="Enter ebook title" required>
                    </div>
                    @error('title')
                        <p class="text-sm text-red-500 flex items-center space-x-1">
                            <i class="ri-error-warning-line"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Price -->
                <div class="space-y-2">
                    <label for="price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Price</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ri-money-dollar-circle-line text-gray-400"></i>
                        </div>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                               class="form-input pl-10 @error('price') border-red-500 @enderror" 
                               placeholder="0.00" required>
                    </div>
                    @error('price')
                        <p class="text-sm text-red-500 flex items-center space-x-1">
                            <i class="ri-error-warning-line"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Cover Image -->
                <div class="md:col-span-2 space-y-2">
                    <label for="cover_image" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Cover Image</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-primary dark:hover:border-primary transition-colors bg-gray-50 dark:bg-gray-800">
                        <div class="space-y-2 text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center mx-auto">
                                <i class="ri-image-line text-white text-2xl"></i>
                            </div>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-lg font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary px-4 py-2 transition-colors">
                                    <span>Upload image</span>
                                    <input type="file" name="cover_image" id="cover_image" accept="image/*"
                                           class="sr-only @error('cover_image') border-red-500 @enderror">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                    @error('cover_image')
                        <p class="text-sm text-red-500 flex items-center space-x-1">
                            <i class="ri-error-warning-line"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2 space-y-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Description</label>
                    <div class="relative">
                        <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                            <i class="ri-file-text-line text-gray-400 mt-0.5"></i>
                        </div>
                        <textarea name="description" id="description" rows="4" 
                                  class="form-input pl-10 @error('description') border-red-500 @enderror" 
                                  placeholder="Describe your ebook content..." required>{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="text-sm text-red-500 flex items-center space-x-1">
                            <i class="ri-error-warning-line"></i>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm p-8 card-shadow">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-folder-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Categories Structure</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Organize your ebook content with categories</p>
                    </div>
                </div>
                <button type="button" onclick="addRootCategory(); hideNoCategoriesMessage();" 
                        class="px-6 py-3 bg-gradient-to-r from-primary to-primary-light text-white rounded-xl hover:from-primary-dark hover:to-primary-dark transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="ri-add-line"></i>
                    <span>Add Root Category</span>
                </button>
            </div>

            <div id="categoriesContainer" class="space-y-4">
                <!-- Categories will be added here dynamically -->
                <div id="noCategoriesMessage" class="text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl bg-gray-50 dark:bg-gray-800">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="ri-folder-add-line text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">No categories added yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">Start by adding a root category to organize your ebook content and create a structured learning experience.</p>
                    <button type="button" onclick="addRootCategory(); hideNoCategoriesMessage();" 
                            class="px-6 py-3 bg-gradient-to-r from-primary to-primary-light text-white rounded-xl hover:from-primary-dark hover:to-primary-dark transition-all duration-200 flex items-center space-x-2 mx-auto shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="ri-add-line"></i>
                        <span>Add First Category</span>
                    </button>
                </div>
            </div>

            @error('categories')
                <p class="mt-4 text-sm text-red-500 flex items-center space-x-1">
                    <i class="ri-error-warning-line"></i>
                    <span>{{ $message }}</span>
                </p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" id="submitBtn" class="px-8 py-4 bg-gradient-to-r from-primary to-primary-light text-white rounded-xl hover:from-primary-dark hover:to-primary-dark transition-all duration-200 flex items-center space-x-3 shadow-lg hover:shadow-xl transform hover:scale-105">
                <span id="submitText">Create Ebook</span>
                <div id="submitSpinner" class="hidden">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </button>
        </div>
    </form>
</div>

<!-- Alert Container -->
<div id="alertContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<!-- Category Template -->
<template id="categoryTemplate">
    <div class="category-item bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 mb-4 relative shadow-sm hover:shadow-md transition-all duration-200" data-level="0">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center space-x-3">
                <button type="button" class="collapse-btn focus:outline-none transition-transform p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg" onclick="toggleCollapse(this)">
                    <i class="ri-arrow-down-s-line text-xl transition-transform text-gray-600 dark:text-gray-400"></i>
                </button>
                <div class="category-icon-container">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center text-white category-icon-display shadow-lg">
                        <i class="ri-folder-line"></i>
                    </div>
                    <div class="w-10 h-10 rounded-xl overflow-hidden border border-gray-300 dark:border-gray-600 hidden category-image-preview shadow-lg">
                        <img src="" alt="" class="w-full h-full object-cover">
                    </div>
                </div>
                <span class="font-semibold text-gray-900 dark:text-white category-summary text-lg">Category</span>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="addSubcategory(this)" 
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 flex items-center space-x-2 shadow-md hover:shadow-lg">
                    <i class="ri-add-line"></i>
                    <span>Add Subcategory</span>
                </button>
                <button type="button" onclick="removeCategory(this)" 
                        class="p-3 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200">
                    <i class="ri-delete-bin-line text-lg"></i>
                </button>
            </div>
        </div>
        <div class="category-details grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="categories[INDEX][name]" class="form-input category-name-input" placeholder="Category name" required oninput="updateCategorySummary(this)">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Icon (optional)</label>
                <input type="text" name="categories[INDEX][icon]" class="form-input category-icon-input" placeholder="ri-folder-line">
            </div>
            <div class="md:col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Description (optional)</label>
                <textarea name="categories[INDEX][description]" class="form-input category-description-input" rows="3" placeholder="Brief description of this category"></textarea>
            </div>
            <div class="md:col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Category Image (optional)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-primary dark:hover:border-primary transition-colors bg-gray-50 dark:bg-gray-800 category-image-upload">
                    <div class="space-y-2 text-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center mx-auto">
                            <i class="ri-image-line text-white text-xl"></i>
                        </div>
                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                            <label class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-lg font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary px-4 py-2 transition-colors">
                                <span>Upload image</span>
                                <input type="file" name="categories[INDEX][image]" 
                                       class="sr-only category-image-input" accept="image/*" onchange="updateCategoryImageName(this)">
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB</p>
                        <p class="text-xs text-green-600 dark:text-green-400 hidden category-image-name"></p>
                    </div>
                </div>
            </div>
            <!-- Hidden input to track parent relationship -->
            <input type="hidden" name="categories[INDEX][parent_index]" value="" class="parent-index-input">
            <input type="hidden" name="categories[INDEX][level]" value="0" class="level-input">
        </div>
        <div class="collapsible-children transition-all duration-300 ease-in-out overflow-hidden">
            <!-- Subcategories Container -->
            <div class="subcategories-container space-y-4 ml-8 border-l-2 border-gray-200 dark:border-gray-700 pl-6">
                <!-- Subcategories will be added here dynamically -->
            </div>
            <!-- Resource Container (only for leaf nodes) -->
            <div class="resource-container mt-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                            <i class="ri-file-line text-white text-sm"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Resource</h5>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Only available for leaf nodes</p>
                        </div>
                    </div>
                    <button type="button" onclick="toggleResource(this)" 
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 text-sm flex items-center space-x-2">
                        <i class="ri-add-line"></i>
                        <span>Add Resource</span>
                    </button>
                </div>
                <div class="resource-content hidden">
                    <!-- Resource content will be added here -->
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Resource Template -->
<template id="resourceTemplate">
    <div class="resource-item bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6">
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i class="ri-file-line"></i>
                </div>
                <h6 class="text-lg font-semibold text-gray-900 dark:text-white">Resource</h6>
            </div>
            <button type="button" onclick="removeResource(this)" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200">
                <i class="ri-delete-bin-line text-lg"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="categories[CATEGORY_INDEX][resource][title]" class="form-input resource-title-input" placeholder="Resource title">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Content Type</label>
                <select name="categories[CATEGORY_INDEX][resource][content_type]" 
                        class="form-input resource-content-type-input" onchange="handleContentTypeChange(this)">
                    <option value="">Select file type</option>
                    <option value="pdf">PDF Document</option>
                    <option value="excel">Excel File (.xlsx, .xls)</option>
                </select>
            </div>
            <div class="md:col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Description (optional)</label>
                <textarea name="categories[CATEGORY_INDEX][resource][description]" class="form-input" rows="3" placeholder="Brief description of the resource content"></textarea>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="content-container">
            <div class="file-upload-container">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Upload File <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-primary dark:hover:border-primary transition-colors bg-gray-50 dark:bg-gray-800">
                    <div class="space-y-2 text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center mx-auto">
                            <i class="ri-upload-line text-white text-2xl"></i>
                        </div>
                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                            <label class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-lg font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary px-4 py-2 transition-colors">
                                <span>Upload a file</span>
                                <input type="file" name="categories[CATEGORY_INDEX][resource][file]" 
                                       class="sr-only file-input resource-file-input" accept=".pdf,.xlsx,.xls" onchange="updateFileName(this)">
                            </label>
                            <p class="pl-2 self-center">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PDF, Excel files up to 20MB</p>
                        <p class="text-xs text-green-600 dark:text-green-400 hidden file-name"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

@push('styles')
<style>
.collapse-btn .ri-arrow-down-s-line {
    transition: transform 0.3s;
}
.collapse-btn.collapsed .ri-arrow-down-s-line {
    transform: rotate(-90deg);
}
.collapsible-children {
    max-height: none;
    transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
}
.collapsible-children.collapsed {
    max-height: 0 !important;
    opacity: 0;
    pointer-events: none;
}
.category-summary {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
}
.dark .category-summary {
    color: #f3f4f6;
}
.file-upload-container .border-dashed:hover {
    border-color: #8622c7;
    background-color: #f8fafc;
}
.dark .file-upload-container .border-dashed:hover {
    background-color: #1f2937;
}
.file-upload-container input[type="file"]:focus + label {
    outline: 2px solid #8622c7;
    outline-offset: 2px;
}
.category-image-upload .border-dashed:hover {
    border-color: #8622c7;
    background-color: #f8fafc;
}
.dark .category-image-upload .border-dashed:hover {
    background-color: #1f2937;
}
.category-image-upload input[type="file"]:focus + label {
    outline: 2px solid #8622c7;
    outline-offset: 2px;
}

/* Enhanced form inputs */
.form-input {
    @apply w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200;
}

.form-input:focus {
    @apply shadow-lg;
}

/* Card shadow utility */
.card-shadow {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.dark .card-shadow {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
}
</style>
@endpush

@push('scripts')
<script>
let categoryIndex = 0;

function updateCategorySummary(input) {
    const categoryItem = input.closest('.category-item');
    const summary = categoryItem.querySelector('.category-summary');
    summary.textContent = input.value ? input.value : 'Category';
}

function toggleCollapse(button) {
    button.classList.toggle('collapsed');
    const categoryItem = button.closest('.category-item');
    const collapsible = categoryItem.querySelector('.collapsible-children');
    if (collapsible) {
        collapsible.classList.toggle('collapsed');
    }
}

function updateLeafResourceContainersRecursive(container) {
    Array.from(container.children).forEach((child, idx) => {
        if (!child.classList.contains('category-item')) return;
        
        const subcategories = child.querySelector(':scope > .collapsible-children > .subcategories-container');
        const resourceContainer = child.querySelector(':scope > .collapsible-children > .resource-container');
        
        if (resourceContainer) {
            // Check if this category has any subcategories
            const hasSubcategories = subcategories && subcategories.children.length > 0;
            
            if (hasSubcategories) {
                // Hide resource container if category has subcategories
                resourceContainer.style.display = 'none';
                console.log('Hiding resource for category with subcategories:', child.querySelector('input[name*="name"]')?.value);
            } else {
                // Show resource container if category is a leaf node
                resourceContainer.style.display = 'block';
                console.log('Showing resource for leaf category:', child.querySelector('input[name*="name"]')?.value);
            }
        }
        
        // Recursively update for all subcategories
        if (subcategories) {
            updateLeafResourceContainersRecursive(subcategories);
        }
    });
}

function hideNoCategoriesMessage() {
    const message = document.getElementById('noCategoriesMessage');
    if (message) {
        message.style.display = 'none';
    }
}

function showNoCategoriesMessage() {
    const message = document.getElementById('noCategoriesMessage');
    const categories = document.querySelectorAll('.category-item');
    if (message && categories.length === 0) {
        message.style.display = 'block';
    }
}

function addRootCategory() {
    const template = document.getElementById('categoryTemplate');
    const container = document.getElementById('categoriesContainer');
    const clone = template.content.cloneNode(true);
    const categoryId = categoryIndex++;
    const content = clone.querySelector('.category-item').innerHTML;
    
    console.log('=== ADD ROOT CATEGORY DEBUG ===');
    console.log('categoryId:', categoryId);
    console.log('Original content sample:', content.substring(0, 200));
    console.log('Content contains INDEX:', content.includes('INDEX'));
    
    const updatedContent = content.replace(/INDEX/g, categoryId);
    
    console.log('After replacement - content contains INDEX:', updatedContent.includes('INDEX'));
    console.log('After replacement - content contains categoryId:', updatedContent.includes(categoryId));
    console.log('Updated content sample:', updatedContent.substring(0, 200));
    
    const wrapper = document.createElement('div');
    wrapper.className = 'category-item bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 mb-4 relative shadow-sm hover:shadow-md transition-all duration-200';
    wrapper.innerHTML = updatedContent;
    container.appendChild(wrapper);
    
    // Log the actual input names created
    const inputs = wrapper.querySelectorAll('input[name*="categories"], textarea[name*="categories"]');
    console.log('=== CREATED FORM FIELDS ===');
    inputs.forEach(input => {
        console.log('Field name:', input.name, 'Type:', input.type || input.tagName);
    });
    
    // Also check all form fields in the entire form
    setTimeout(() => {
        const allFormFields = document.querySelectorAll('#ebookForm input[name*="categories"], #ebookForm textarea[name*="categories"]');
        console.log('=== ALL CATEGORY FORM FIELDS ===');
        allFormFields.forEach(input => {
            console.log('Field name:', input.name, 'Value:', input.value, 'Type:', input.type || input.tagName);
        });
    }, 100);
    
    updateLeafResourceContainersRecursive(document.getElementById('categoriesContainer'));
}

function addSubcategory(button) {
    const template = document.getElementById('categoryTemplate');
    const categoryItem = button.closest('.category-item');
    const container = categoryItem.querySelector('.subcategories-container');
    const clone = template.content.cloneNode(true);
    const parentCategoryId = categoryItem.querySelector('input[name^="categories["]').name.match(/\[(\d+)\]/)[1];
    const level = parseInt(categoryItem.dataset.level || 0) + 1;
    const subcategoryId = categoryIndex++;
    const content = clone.querySelector('.category-item').innerHTML;
    const updatedContent = content.replace(/INDEX/g, subcategoryId).replace(/CATEGORY_INDEX/g, subcategoryId);
    const wrapper = document.createElement('div');
    wrapper.className = 'category-item bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 mb-4 relative shadow-sm hover:shadow-md transition-all duration-200';
    wrapper.dataset.level = level;
    wrapper.innerHTML = updatedContent;
    
    // Set parent relationship
    const parentIndexInput = wrapper.querySelector('.parent-index-input');
    const levelInput = wrapper.querySelector('.level-input');
    if (parentIndexInput) parentIndexInput.value = parentCategoryId;
    if (levelInput) levelInput.value = level;
    
    container.appendChild(wrapper);
    
    // Update resource visibility for all categories
    setTimeout(() => {
        updateLeafResourceContainersRecursive(document.getElementById('categoriesContainer'));
    }, 0);
}

function removeCategory(button) {
    const categoryItem = button.closest('.category-item');
    categoryItem.remove();
    updateLeafResourceContainersRecursive(document.getElementById('categoriesContainer'));
    
    // Show no categories message if no categories left
    setTimeout(() => {
        showNoCategoriesMessage();
    }, 100);
}

function toggleResource(button) {
    const categoryItem = button.closest('.category-item');
    const resourceContainer = categoryItem.querySelector('.resource-container');
    const resourceContent = categoryItem.querySelector('.resource-content');
    const subcategoriesContainer = categoryItem.querySelector('.subcategories-container');
    
    // Check if this category has subcategories (not a leaf node)
    if (subcategoriesContainer && subcategoriesContainer.children.length > 0) {
        alert('Resources can only be added to leaf categories (categories without subcategories). Please add the resource to a subcategory instead.');
        return;
    }
    
    if (resourceContent.classList.contains('hidden')) {
        const template = document.getElementById('resourceTemplate');
        const clone = template.content.cloneNode(true);
        const categoryId = categoryItem.querySelector('input[name^="categories["]').name.match(/\[(\d+)\]/)[1];
        const content = clone.querySelector('.resource-item').innerHTML;
        const updatedContent = content.replace(/CATEGORY_INDEX/g, categoryId);
        resourceContent.innerHTML = updatedContent;
        resourceContent.classList.remove('hidden');
        
        // Add required attributes to resource fields when shown
        const resourceInputs = resourceContent.querySelectorAll('.resource-title-input, .resource-content-type-input, .resource-file-input');
        resourceInputs.forEach(input => {
            input.setAttribute('required', 'required');
        });
        
        button.innerHTML = '<i class="ri-delete-bin-line"></i> Remove Resource';
        button.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
        button.classList.add('bg-red-100', 'dark:bg-red-900', 'text-red-700', 'dark:text-red-300', 'hover:bg-red-200', 'dark:hover:bg-red-800');
    } else {
        // Remove required attributes before hiding
        const resourceInputs = resourceContent.querySelectorAll('.resource-title-input, .resource-content-type-input, .resource-file-input');
        resourceInputs.forEach(input => {
            input.removeAttribute('required');
        });
        
        resourceContent.innerHTML = '';
        resourceContent.classList.add('hidden');
        button.innerHTML = '<i class="ri-add-line"></i> Add Resource';
        button.classList.remove('bg-red-100', 'dark:bg-red-900', 'text-red-700', 'dark:text-red-300', 'hover:bg-red-200', 'dark:hover:bg-red-800');
        button.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
    }
}

function removeResource(button) {
    const resourceItem = button.closest('.resource-item');
    const resourceContent = resourceItem.closest('.resource-content');
    const toggleButton = resourceContent.previousElementSibling.querySelector('button');
    
    // Remove required attributes before hiding
    const resourceInputs = resourceContent.querySelectorAll('.resource-title-input, .resource-content-type-input, .resource-file-input');
    resourceInputs.forEach(input => {
        input.removeAttribute('required');
    });
    
    resourceContent.innerHTML = '';
    resourceContent.classList.add('hidden');
    toggleButton.innerHTML = '<i class="ri-add-line"></i> <span>Add Resource</span>';
    toggleButton.classList.remove('bg-red-100', 'dark:bg-red-900', 'text-red-700', 'dark:text-red-300', 'hover:bg-red-200', 'dark:hover:bg-red-800');
    toggleButton.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-200', 'dark:hover:bg-gray-600');
}

function handleContentTypeChange(select) {
    const resourceItem = select.closest('.resource-item');
    const fileInput = resourceItem.querySelector('input[type="file"]');
    
    // Update file input accept attribute based on selection
    if (select.value === 'pdf') {
        fileInput.accept = '.pdf';
    } else if (select.value === 'excel') {
        fileInput.accept = '.xlsx,.xls';
    }
}

function updateFileName(input) {
    const fileNameDisplay = input.closest('.file-upload-container').querySelector('.file-name');
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2); // Convert to MB
        fileNameDisplay.textContent = `Selected: ${fileName} (${fileSize} MB)`;
        fileNameDisplay.classList.remove('hidden');
    } else {
        fileNameDisplay.classList.add('hidden');
    }
}

function updateCategoryImageName(input) {
    const fileNameDisplay = input.closest('.category-image-upload').querySelector('.category-image-name');
    const categoryItem = input.closest('.category-item');
    const iconDisplay = categoryItem.querySelector('.category-icon-display');
    const imagePreview = categoryItem.querySelector('.category-image-preview');
    const previewImg = imagePreview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2); // Convert to MB
        fileNameDisplay.textContent = `Selected: ${fileName} (${fileSize} MB)`;
        fileNameDisplay.classList.remove('hidden');
        
        // Show image preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.alt = fileName;
            iconDisplay.classList.add('hidden');
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        fileNameDisplay.classList.add('hidden');
        iconDisplay.classList.remove('hidden');
        imagePreview.classList.add('hidden');
        previewImg.src = '';
    }
}

// Alert functions
function showAlert(message, type = 'success') {
    const alertContainer = document.getElementById('alertContainer');
    const alertId = 'alert-' + Date.now();
    
    const alertColors = {
        success: 'bg-gradient-to-r from-green-500 to-green-600',
        error: 'bg-gradient-to-r from-red-500 to-red-600',
        warning: 'bg-gradient-to-r from-yellow-500 to-yellow-600',
        info: 'bg-gradient-to-r from-blue-500 to-blue-600'
    };
    
    const alertHtml = `
        <div id="${alertId}" class="${alertColors[type]} text-white px-6 py-4 rounded-xl shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <span class="font-medium">${message}</span>
                <button onclick="hideAlert('${alertId}')" class="ml-4 text-white hover:text-gray-200 transition-colors">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>
        </div>
    `;
    
    alertContainer.insertAdjacentHTML('beforeend', alertHtml);
    
    // Animate in
    setTimeout(() => {
        document.getElementById(alertId).classList.remove('translate-x-full');
    }, 100);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        hideAlert(alertId);
    }, 5000);
}

function hideAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.classList.add('translate-x-full');
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
}

function setLoadingState(loading) {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    if (loading) {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        submitText.textContent = 'Creating Ebook...';
        submitSpinner.classList.remove('hidden');
    } else {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        submitText.textContent = 'Create Ebook';
        submitSpinner.classList.add('hidden');
    }
}

// Add form submission handler to structure data properly
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission
    const form = document.getElementById('ebookForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submission started');
            
            // Validate that at least one category exists and has a name
            const categories = document.querySelectorAll('.category-item');
            let hasValidCategory = false;
            
            categories.forEach(category => {
                const nameInput = category.querySelector('input[name*="name"]');
                if (nameInput && nameInput.value.trim() !== '') {
                    hasValidCategory = true;
                }
            });
            
            if (!hasValidCategory) {
                e.preventDefault();
                showAlert('Please add at least one category with a name.', 'error');
                return false;
            }
            
            // Remove empty categories before submission
            categories.forEach(category => {
                const nameInput = category.querySelector('input[name*="name"]');
                if (nameInput && nameInput.value.trim() === '') {
                    category.remove();
                }
            });
            
            // Show loading state
            setLoadingState(true);
            showAlert('Creating ebook... Please wait.', 'info');
            
            // Log form data for debugging
            console.log('=== FORM SUBMISSION DATA ===');
            const formData = new FormData(this);
            const categoryFields = [];
            for (let [key, value] of formData.entries()) {
                if (key.includes('categories')) {
                    categoryFields.push({name: key, value: value instanceof File ? 'FILE: ' + value.name : value});
                }
            }
            console.log('Category fields being sent:', categoryFields);
            
            // The form now uses correct field names like categories[0][name] instead of categories[0].name
            // So Laravel should receive the data in the correct nested format automatically
            console.log('Form data is now properly structured for Laravel');
            
            // Debug: Show all form field names
            console.log('=== ALL FORM FIELDS ===');
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value instanceof File ? 'FILE: ' + value.name : value);
            }
            
            // Let the form submit normally
            return true;
        });
    }
    
    // Handle success/error messages from Laravel
    @if(session('success'))
        showAlert('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showAlert('{{ session('error') }}', 'error');
    @endif
    
    @if($errors->any())
        @foreach($errors->all() as $error)
            showAlert('{{ $error }}', 'error');
        @endforeach
        // Reset loading state if there are errors
        setLoadingState(false);
    @endif
});
</script>
@endpush
@endsection 