@extends('layouts.app')

@section('title', 'Gestion des Coupons')
@section('page-title', 'Gestion des Coupons')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Coupons</h2>
            <p class="text-gray-600 dark:text-gray-400">Créez et gérez vos codes de réduction</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" 
           class="px-6 py-3 bg-gradient-to-r from-primary to-primary-light text-white rounded-xl hover:from-primary-dark hover:to-primary-dark transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-105">
            <i class="ri-add-line text-lg"></i>
            <span>Nouveau Coupon</span>
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-coupon-line text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-light">Total Coupons</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-check-line text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-light">Actifs</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-time-line text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-light">Expirés</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['expired'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-user-line text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-light">Utilisations</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_usage'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rechercher</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Code ou description..."
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Statut</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">Tous</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactifs</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirés</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">Tous</option>
                    <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Pourcentage</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Montant fixe</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    <i class="ri-search-line mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl overflow-hidden card-shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valeur</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Utilisations</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Validité</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($coupons as $coupon)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center">
                                    <i class="ri-coupon-line text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white font-mono">{{ $coupon->code }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Créé {{ $coupon->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900 dark:text-white">{{ $coupon->description ?: 'Aucune description' }}</p>
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $coupon->type_badge_class }}">
                                {{ $coupon->formatted_type }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $coupon->formatted_value }}</p>
                            @if($coupon->minimum_amount)
                                <p class="text-xs text-gray-500 dark:text-gray-400">Min: {{ $coupon->formatted_minimum_amount }}</p>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $coupon->status_badge_class }}">
                                {{ $coupon->formatted_status }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full" style="width: {{ $coupon->usage_percentage }}%"></div>
                                </div>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $coupon->used_count }}</span>
                                @if($coupon->usage_limit)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">/ {{ $coupon->usage_limit }}</span>
                                @endif
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900 dark:text-white">{{ $coupon->validity_period }}</p>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.coupons.show', $coupon) }}" 
                                   class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                   class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce coupon ?')"
                                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center space-y-4">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                                    <i class="ri-coupon-line text-2xl text-gray-400"></i>
                                </div>
                                <div>
                                    <p class="text-lg font-medium text-gray-900 dark:text-white">Aucun coupon trouvé</p>
                                    <p class="text-gray-500 dark:text-gray-400">Commencez par créer votre premier coupon</p>
                                </div>
                                <a href="{{ route('admin.coupons.create') }}" 
                                   class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                    Créer un coupon
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($coupons->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $coupons->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection 