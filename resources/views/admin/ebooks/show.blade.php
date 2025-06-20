@extends('layouts.app')

@section('title', 'Ebook Details')
@section('page-title', $ebook->title)

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="space-y-6">
    <!-- Ebook Information -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $ebook->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Price</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">${{ number_format($ebook->price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $ebook->description }}</dd>
                    </div>
                </dl>
            </div>
            
            @if($ebook->cover_image)
            <div>
                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2">Cover Image</h4>
                <img src="{{ Storage::url($ebook->cover_image) }}" alt="Cover" class="w-48 h-64 object-cover rounded-lg">
            </div>
            @endif
        </div>
    </div>

    <!-- Categories and Resources -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Categories & Resources</h3>
        
        @if($ebook->categories->count() > 0)
            <div class="space-y-4">
                @foreach($ebook->categories as $category)
                    @include('admin.ebooks.partials.category-tree', ['category' => $category, 'level' => 0])
                @endforeach
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400">No categories found.</p>
        @endif
    </div>

    <!-- Actions -->
    <div class="flex justify-between">
        <a href="{{ route('admin.ebooks.index') }}" 
           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
            Back to List
        </a>
        <div class="space-x-2">
            <a href="{{ route('admin.ebooks.edit', $ebook) }}" 
               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                Edit
            </a>
            <form action="{{ route('admin.ebooks.destroy', $ebook) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('Are you sure you want to delete this ebook?')"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
