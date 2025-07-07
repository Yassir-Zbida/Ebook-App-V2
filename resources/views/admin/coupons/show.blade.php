@extends('layouts.app')

@section('title', 'Détails du Coupon')
@section('page-title', $coupon->code)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary to-primary-light p-6 rounded-2xl shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="ri-coupon-line text-2xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-white mb-1">{{ $coupon->code }}</h1>
                        <p class="text-purple-100">{{ $coupon->description ?: 'Aucune description' }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $coupon->status_badge_class }} bg-white/20 backdrop-blur-sm">
                        {{ $coupon->formatted_status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Coupon Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm p-6 card-shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations du coupon</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Code</p>
                        <p class="text-lg font-mono font-semibold text-gray-900 dark:text-white">{{ $coupon->code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Type</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $coupon->type_badge_class }}">
                            {{ $coupon->formatted_type }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Valeur</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $coupon->formatted_value }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Montant minimum</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $coupon->formatted_minimum_amount ?: 'Aucun' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Utilisations</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Limite par utilisateur</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $coupon->usage_limit_per_user ?: 'Illimitée' }}</p>
                    </div>
                </div>
            </div>

            <!-- Validity Period -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm p-6 card-shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Période de validité</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Date de début</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $coupon->valid_from ? $coupon->valid_from->format('d/m/Y H:i') : 'Immédiat' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Date de fin</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $coupon->valid_until ? $coupon->valid_until->format('d/m/Y H:i') : 'Illimitée' }}
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Statut de validité</p>
                    <p class="text-lg font-semibold {{ $coupon->isValid() ? 'text-green-600' : 'text-red-600' }}">
                        {{ $coupon->isValid() ? 'Valide' : 'Non valide' }}
                    </p>
                </div>
            </div>

            <!-- Usage History -->
            @if($coupon->usages->count() > 0)
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm p-6 card-shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Historique d'utilisation</h3>
                <div class="space-y-3">
                    @foreach($coupon->usages as $usage)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center">
                                <i class="ri-user-line text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $usage->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Commande #{{ $usage->order->id }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($usage->discount_amount, 2) }} €</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $usage->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm p-6 card-shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistiques</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Utilisations</span>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $coupon->used_count }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Taux d'utilisation</span>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ number_format($coupon->usage_percentage, 1) }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Utilisations restantes</span>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $coupon->remaining_uses ?: 'Illimité' }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm p-6 card-shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                       class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        <i class="ri-edit-line"></i>
                        <span>Modifier</span>
                    </a>
                    
                    <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full flex items-center justify-center space-x-2 px-4 py-2 {{ $coupon->is_active ? 'bg-orange-500 hover:bg-orange-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-lg transition-colors">
                            <i class="ri-{{ $coupon->is_active ? 'pause' : 'play' }}-line"></i>
                            <span>{{ $coupon->is_active ? 'Désactiver' : 'Activer' }}</span>
                        </button>
                    </form>
                    
                    @if($coupon->used_count === 0)
                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce coupon ?')"
                                class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            <i class="ri-delete-bin-line"></i>
                            <span>Supprimer</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            @if($coupon->metadata)
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm p-6 card-shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Métadonnées</h3>
                <div class="space-y-2 text-sm">
                    @if(isset($coupon->metadata['created_by']))
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Créé par:</span>
                        <span class="text-gray-900 dark:text-white">ID {{ $coupon->metadata['created_by'] }}</span>
                    </div>
                    @endif
                    @if(isset($coupon->metadata['created_at']))
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Créé le:</span>
                        <span class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($coupon->metadata['created_at'])->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    @if(isset($coupon->metadata['updated_at']))
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Modifié le:</span>
                        <span class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($coupon->metadata['updated_at'])->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-start">
        <a href="{{ route('admin.coupons.index') }}" 
           class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <i class="ri-arrow-left-line mr-2"></i>Retour à la liste
        </a>
    </div>
</div>
@endsection 