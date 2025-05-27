@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="category-item" style="margin-left: {{ $level * 20 }}px;">
    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg mb-2">
        <div class="flex items-center space-x-3">
            @if($category->image)
                <div class="w-12 h-12 rounded-lg overflow-hidden border-2 border-white shadow-sm">
                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                </div>
            @else
                <div class="w-8 h-8 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center text-white">
                    <i class="{{ $category->icon ?? 'ri-folder-line' }}"></i>
                </div>
            @endif
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</h4>
                @if($category->description)
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $category->description }}</p>
                @endif
            </div>
        </div>
        
        @if($category->resources->count() > 0)
            <div class="flex items-center space-x-2">
                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                    {{ $category->resources->count() }} resource(s)
                </span>
            </div>
        @endif
    </div>

    <!-- Resources -->
    @if($category->resources->count() > 0)
        <div class="ml-8 mb-4">
            @foreach($category->resources as $resource)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center text-white">
                                @if($resource->content_type === 'pdf')
                                    <i class="ri-file-pdf-line"></i>
                                @elseif(in_array($resource->content_type, ['excel', 'xlsx']))
                                    <i class="ri-file-excel-line"></i>
                                @else
                                    <i class="ri-file-line"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-900 dark:text-white">{{ $resource->title }}</h5>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Type: {{ ucfirst($resource->content_type) }}
                                </p>
                                @if($resource->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $resource->description }}</p>
                                @endif
                            </div>
                        </div>
                        
                        @if($resource->file_path)
                            <div class="flex items-center space-x-2">
                                <a href="{{ Storage::url($resource->file_path) }}" 
                                   target="_blank"
                                   class="px-3 py-1 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm flex items-center space-x-1">
                                    <i class="ri-download-line"></i>
                                    <span>Download</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Subcategories -->
    @if($category->children->count() > 0)
        <div class="ml-4">
            @foreach($category->children as $subcategory)
                @include('admin.ebooks.partials.category-tree', ['category' => $subcategory, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div> 