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
                <button type="button" onclick="addRootCategory()" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    Add Root Category
                </button>
            </div>

            <div id="categoriesContainer" class="space-y-4">
                <!-- Categories will be added here dynamically -->
            </div>

            @error('categories')
                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                Create Ebook
            </button>
        </div>
    </form>
</div>

<!-- Category Template -->
<template id="categoryTemplate">
    <div class="category-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-2 relative" data-level="0">
        <div class="flex justify-between items-center mb-2">
            <div class="flex items-center space-x-2">
                <button type="button" class="collapse-btn focus:outline-none transition-transform" onclick="toggleCollapse(this)">
                    <i class="ri-arrow-down-s-line text-xl transition-transform"></i>
                </button>
                <div class="w-7 h-7 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center text-white">
                    <i class="ri-folder-line"></i>
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
                <input type="text" name="categories[INDEX].name" class="form-input form-input-sm" required oninput="updateCategorySummary(this)">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Icon (optional)</label>
                <input type="text" name="categories[INDEX].icon" class="form-input form-input-sm" placeholder="ri-folder-line">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Description (optional)</label>
                <textarea name="categories[INDEX].description" class="form-input form-input-sm" rows="2"></textarea>
            </div>
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
                            class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-xs">
                        Add Resource
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
                <input type="text" name="categories[CATEGORY_INDEX].resource.title" class="form-input" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content Type</label>
                <select name="categories[CATEGORY_INDEX].resource.content_type" 
                        class="form-input" onchange="handleContentTypeChange(this)" required>
                    <option value="pdf">PDF Document</option>
                    <option value="excel">Excel File</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description (optional)</label>
                <textarea name="categories[CATEGORY_INDEX].resource.description" class="form-input" rows="2"></textarea>
            </div>
        </div>

        <!-- Dynamic Content Based on Type -->
        <div class="content-container">
            <!-- File Upload for PDF/Excel -->
            <div class="file-upload-container">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File</label>
                <input type="file" name="categories[CATEGORY_INDEX].resource.file" 
                       class="form-input" accept=".pdf,.xlsx,.xls" required>
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
        const res = child.querySelector(':scope > .collapsible-children > .resource-container');
        // Debug log
        console.log('Processing category-item', idx, child, 'subcategories:', subcategories, 'resource:', res);
        if (res) {
            if (subcategories && subcategories.children.length === 0) {
                res.style.display = 'block';
                console.log('Showing resource for', child);
            } else {
                res.style.display = 'none';
                console.log('Hiding resource for', child);
            }
        }
        // Recursively update for all subcategories
        if (subcategories) updateLeafResourceContainersRecursive(subcategories);
    });
}

function addRootCategory() {
    const template = document.getElementById('categoryTemplate');
    const container = document.getElementById('categoriesContainer');
    const clone = template.content.cloneNode(true);
    const categoryId = categoryIndex++;
    const content = clone.querySelector('.category-item').innerHTML;
    const updatedContent = content.replace(/INDEX/g, categoryId);
    const wrapper = document.createElement('div');
    wrapper.className = 'category-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-2 relative';
    wrapper.innerHTML = updatedContent;
    container.appendChild(wrapper);
    updateLeafResourceContainersRecursive(document.getElementById('categoriesContainer'));
}

function addSubcategory(button) {
    const template = document.getElementById('categoryTemplate');
    const categoryItem = button.closest('.category-item');
    const container = categoryItem.querySelector('.subcategories-container');
    const clone = template.content.cloneNode(true);
    const categoryId = categoryItem.querySelector('input[name^="categories["]').name.match(/\[(\d+)\]/)[1];
    const level = parseInt(categoryItem.dataset.level) + 1;
    const subcategoryId = categoryIndex++;
    const content = clone.querySelector('.category-item').innerHTML;
    const updatedContent = content.replace(/INDEX/g, subcategoryId).replace(/CATEGORY_INDEX/g, categoryId);
    const wrapper = document.createElement('div');
    wrapper.className = 'category-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-2 relative';
    wrapper.dataset.level = level;
    wrapper.innerHTML = updatedContent;
    container.appendChild(wrapper);
    // Defer update to next event loop to ensure DOM is ready
    setTimeout(() => updateLeafResourceContainersRecursive(document.getElementById('categoriesContainer')), 0);
    // Hide resource container of parent category
    const resourceContainer = categoryItem.querySelector('.resource-container');
    if (resourceContainer) {
        resourceContainer.style.display = 'none';
    }
}

function removeCategory(button) {
    const categoryItem = button.closest('.category-item');
    categoryItem.remove();
    updateLeafResourceContainersRecursive(document.getElementById('categoriesContainer'));
}

function toggleResource(button) {
    const categoryItem = button.closest('.category-item');
    const resourceContainer = categoryItem.querySelector('.resource-container');
    const resourceContent = categoryItem.querySelector('.resource-content');
    const subcategoriesContainer = categoryItem.querySelector('.subcategories-container');
    if (subcategoriesContainer && subcategoriesContainer.children.length > 0) {
        alert('Resources can only be added to the last subcategory in the hierarchy. Please add the resource to the deepest subcategory instead.');
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
        button.textContent = 'Remove Resource';
    } else {
        resourceContent.innerHTML = '';
        resourceContent.classList.add('hidden');
        button.textContent = 'Add Resource';
    }
}

function removeResource(button) {
    const resourceItem = button.closest('.resource-item');
    const resourceContent = resourceItem.closest('.resource-content');
    const toggleButton = resourceContent.previousElementSibling.querySelector('button');
    resourceContent.innerHTML = '';
    resourceContent.classList.add('hidden');
    toggleButton.textContent = 'Add Resource';
}

function handleContentTypeChange(select) {
    // Since we only have file uploads now (PDF/Excel), no need to toggle containers
    const resourceItem = select.closest('.resource-item');
    const fileInput = resourceItem.querySelector('input[type="file"]');
    
    // Update file input accept attribute based on selection
    if (select.value === 'pdf') {
        fileInput.accept = '.pdf';
    } else if (select.value === 'excel') {
        fileInput.accept = '.xlsx,.xls';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    addRootCategory();
});
</script>
@endpush
@endsection
