@extends('layouts.app')

@section('title', 'Create New Ebook')
@section('page-title', 'Create New Ebook')

@section('content')
<div class="space-y-6">
    <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data" id="ebookForm">
        @csrf
        
        <!-- Basic Ebook Information -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                           class="form-input @error('title') border-red-500 @enderror" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                           class="form-input @error('price') border-red-500 @enderror" required>
                    @error('price')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cover Image -->
                <div class="md:col-span-2">
                    <label for="cover_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cover Image</label>
                    <input type="file" name="cover_image" id="cover_image" accept="image/*"
                           class="form-input @error('cover_image') border-red-500 @enderror">
                    @error('cover_image')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              class="form-input @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Categories Structure</h3>
                <button type="button" onclick="addRootCategory(); hideNoCategoriesMessage();" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    Add Root Category
                </button>
            </div>

            <div id="categoriesContainer" class="space-y-4">
                <!-- Categories will be added here dynamically -->
                <div id="noCategoriesMessage" class="text-center py-8 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-folder-add-line text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No categories added yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Start by adding a root category to organize your ebook content.</p>
                    <button type="button" onclick="addRootCategory(); hideNoCategoriesMessage();" 
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors inline-flex items-center space-x-2">
                        <i class="ri-add-line"></i>
                        <span>Add First Category</span>
                    </button>
                </div>
            </div>

            @error('categories')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" id="submitBtn" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center space-x-2">
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
    <div class="category-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-2 relative" data-level="0">
        <div class="flex justify-between items-center mb-2">
            <div class="flex items-center space-x-2">
                <button type="button" class="collapse-btn focus:outline-none transition-transform" onclick="toggleCollapse(this)">
                    <i class="ri-arrow-down-s-line text-xl transition-transform"></i>
                </button>
                <div class="category-icon-container">
                    <div class="w-7 h-7 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center text-white category-icon-display">
                        <i class="ri-folder-line"></i>
                    </div>
                    <div class="w-7 h-7 rounded-lg overflow-hidden border border-gray-300 hidden category-image-preview">
                        <img src="" alt="" class="w-full h-full object-cover">
                    </div>
                </div>
                <span class="font-medium text-gray-900 dark:text-white category-summary">Category</span>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="addSubcategory(this)" 
                        class="px-3 py-1 bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors flex items-center space-x-2">
                    <i class="ri-add-line"></i>
                    <span>Add Subcategory</span>
                </button>
                <button type="button" onclick="removeCategory(this)" 
                        class="p-2 text-red-500 hover:text-red-700">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
        <div class="category-details grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input type="text" name="categories[INDEX][name]" class="form-input form-input-sm category-name-input" required oninput="updateCategorySummary(this)">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Icon (optional)</label>
                <input type="text" name="categories[INDEX][icon]" class="form-input form-input-sm category-icon-input" placeholder="ri-folder-line">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Description (optional)</label>
                <textarea name="categories[INDEX][description]" class="form-input form-input-sm category-description-input" rows="2"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Category Image (optional)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors category-image-upload">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-xs text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                <span>Upload image</span>
                                <input type="file" name="categories[INDEX][image]" 
                                       class="sr-only category-image-input" accept="image/*" onchange="updateCategoryImageName(this)">
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        <p class="text-xs text-green-600 hidden category-image-name"></p>
                    </div>
                </div>
            </div>
            <!-- Hidden input to track parent relationship -->
            <input type="hidden" name="categories[INDEX][parent_index]" value="" class="parent-index-input">
            <input type="hidden" name="categories[INDEX][level]" value="0" class="level-input">
        </div>
        <div class="collapsible-children transition-all duration-300 ease-in-out overflow-hidden">
            <!-- Subcategories Container -->
            <div class="subcategories-container space-y-2 ml-6 border-l-2 border-gray-200 dark:border-gray-700 pl-3">
                <!-- Subcategories will be added here dynamically -->
            </div>
            <!-- Resource Container (only for leaf nodes) -->
            <div class="resource-container mt-3">
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center space-x-2">
                        <h5 class="text-xs font-medium text-gray-700 dark:text-gray-300">Resource</h5>
                        <span class="text-xs text-gray-500 dark:text-gray-400">(Only available for leaf nodes)</span>
                    </div>
                    <button type="button" onclick="toggleResource(this)" 
                            class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-xs flex items-center space-x-1">
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
    <div class="resource-item border border-gray-200 dark:border-gray-700 rounded-lg p-4">
        <div class="flex justify-between items-start mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center text-white">
                    <i class="ri-file-line"></i>
                </div>
                <h6 class="text-md font-medium text-gray-900 dark:text-white">Resource</h6>
            </div>
            <button type="button" onclick="removeResource(this)" class="p-2 text-red-500 hover:text-red-700">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input type="text" name="categories[CATEGORY_INDEX][resource][title]" class="form-input resource-title-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content Type</label>
                <select name="categories[CATEGORY_INDEX][resource][content_type]" 
                        class="form-input resource-content-type-input" onchange="handleContentTypeChange(this)">
                    <option value="">Select file type</option>
                    <option value="pdf">PDF Document</option>
                    <option value="excel">Excel File (.xlsx, .xls)</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description (optional)</label>
                <textarea name="categories[CATEGORY_INDEX][resource][description]" class="form-input" rows="2" placeholder="Brief description of the resource content"></textarea>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="content-container">
            <div class="file-upload-container">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Upload File <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                <span>Upload a file</span>
                                <input type="file" name="categories[CATEGORY_INDEX][resource][file]" 
                                       class="sr-only file-input resource-file-input" accept=".pdf,.xlsx,.xls" onchange="updateFileName(this)">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, Excel files up to 20MB</p>
                        <p class="text-xs text-green-600 hidden file-name"></p>
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
.form-input-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.9rem;
}
.category-summary {
    font-size: 1rem;
    font-weight: 500;
    color: #6b7280;
}
.file-upload-container .border-dashed:hover {
    border-color: #9ca3af;
    background-color: #f9fafb;
}
.file-upload-container input[type="file"]:focus + label {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}
.category-image-upload .border-dashed:hover {
    border-color: #9ca3af;
    background-color: #f9fafb;
}
.category-image-upload input[type="file"]:focus + label {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
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
    wrapper.className = 'category-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-2 relative';
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
    wrapper.className = 'category-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-2 relative';
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
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    const alertHtml = `
        <div id="${alertId}" class="${alertColors[type]} text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="hideAlert('${alertId}')" class="ml-4 text-white hover:text-gray-200">
                    <i class="ri-close-line"></i>
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
