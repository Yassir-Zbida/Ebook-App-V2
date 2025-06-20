@extends('layouts.app')

@section('title', 'Ebooks')
@section('page-title', 'Ebooks Management')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Ebooks</h2>
            <p class="text-gray-600 dark:text-gray-400">Manage your ebook collection</p>
        </div>
        <a href="{{ route('admin.ebooks.create') }}" 
           class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center space-x-2">
            <i class="ri-add-line"></i>
            <span>Create New Ebook</span>
        </a>
    </div>

    <!-- Ebooks Grid -->
    @if($ebooks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ebooks as $ebook)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Cover Image -->
                    <div class="aspect-w-3 aspect-h-4 bg-gray-200 dark:bg-gray-700">
                        @if($ebook->cover_image)
                            <img src="{{ Storage::url($ebook->cover_image) }}" 
                                 alt="{{ $ebook->title }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                                <i class="ri-book-line text-4xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $ebook->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">{{ $ebook->description }}</p>
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-lg font-bold text-primary">${{ number_format($ebook->price, 2) }}</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                {{ $ebook->categories->count() }} categories
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.ebooks.show', $ebook) }}" 
                               class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-center text-sm">
                                View
                            </a>
                            <a href="{{ route('admin.ebooks.edit', $ebook) }}" 
                               class="flex-1 px-3 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-center text-sm">
                                Edit
                            </a>
                            <form action="{{ route('admin.ebooks.destroy', $ebook) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this ebook?')"
                                        class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-book-line text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No ebooks found</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Get started by creating your first ebook.</p>
            <a href="{{ route('admin.ebooks.create') }}" 
               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors inline-flex items-center space-x-2">
                <i class="ri-add-line"></i>
                <span>Create New Ebook</span>
            </a>
        </div>
    @endif
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="successAlert">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="ri-check-circle-line text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="document.getElementById('successAlert').remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="ri-close-line"></i>
            </button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const alert = document.getElementById('successAlert');
            if (alert) alert.remove();
        }, 5000);
    </script>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="errorAlert">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="ri-error-warning-line text-xl"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="document.getElementById('errorAlert').remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="ri-close-line"></i>
            </button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const alert = document.getElementById('errorAlert');
            if (alert) alert.remove();
        }, 5000);
    </script>
@endif
@endsection
