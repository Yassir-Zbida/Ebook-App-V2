@extends('layouts.app')

@section('title', 'Créer un Coupon')
@section('page-title', 'Créer un Coupon')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary to-primary-light p-6 rounded-2xl shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i class="ri-coupon-add-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-white mb-1">Créer un Coupon</h1>
                    <p class="text-purple-100">Créez un nouveau code de réduction pour vos clients</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm p-8 card-shadow">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-information-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Informations de base</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Détails principaux du coupon</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Code -->
                    <div>
                        <label for="code" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Code du coupon <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="code" name="code" 
                                   value="{{ old('code') }}" 
                                   class="form-input w-full pl-4 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   placeholder="EXEMPLE20" required>
                            <button type="button" onclick="generateCode()" 
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 text-gray-400 hover:text-primary transition-colors">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                        @error('code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="form-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                  placeholder="Description optionnelle du coupon">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type and Value -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Type de réduction <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" 
                                    class="form-select w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="">Sélectionner un type</option>
                                <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Montant fixe (€)</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="value" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Valeur <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="value" name="value" 
                                       value="{{ old('value') }}" 
                                       step="0.01" min="0"
                                       class="form-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                       placeholder="20" required>
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500" id="value-suffix">%</div>
                            </div>
                            @error('value')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Minimum Amount -->
                    <div>
                        <label for="minimum_amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Montant minimum (optionnel)
                        </label>
                        <div class="relative">
                            <input type="number" id="minimum_amount" name="minimum_amount" 
                                   value="{{ old('minimum_amount') }}" 
                                   step="0.01" min="0"
                                   class="form-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   placeholder="50.00">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">€</div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Montant minimum de commande pour utiliser ce coupon</p>
                        @error('minimum_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm p-8 card-shadow">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-settings-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Limites d'utilisation</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Contrôlez l'utilisation du coupon</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Usage Limit -->
                    <div>
                        <label for="usage_limit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Limite d'utilisation totale
                        </label>
                        <input type="number" id="usage_limit" name="usage_limit" 
                               value="{{ old('usage_limit') }}" 
                               min="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                               placeholder="100">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Nombre maximum d'utilisations (laissez vide pour illimité)</p>
                        @error('usage_limit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Per User Limit -->
                    <div>
                        <label for="usage_limit_per_user" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Limite par utilisateur
                        </label>
                        <input type="number" id="usage_limit_per_user" name="usage_limit_per_user" 
                               value="{{ old('usage_limit_per_user') }}" 
                               min="1"
                               class="form-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                               placeholder="1">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Nombre maximum d'utilisations par utilisateur</p>
                        @error('usage_limit_per_user')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Validity Period -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="valid_from" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Date de début
                            </label>
                            <input type="datetime-local" id="valid_from" name="valid_from" 
                                   value="{{ old('valid_from') }}" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                            @error('valid_from')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="valid_until" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Date de fin
                            </label>
                            <input type="datetime-local" id="valid_until" name="valid_until" 
                                   value="{{ old('valid_until') }}" 
                                   class="form-input w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                            @error('valid_until')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-5 h-5 text-primary border-gray-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Coupon actif</span>
                        </label>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Le coupon sera immédiatement disponible</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applicability -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm p-8 card-shadow">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-target-line text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Applicabilité</h3>
                    <p class="text-gray-600 dark:text-gray-light text-sm">Définissez sur quels produits le coupon s'applique</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Applicable Ebooks -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Ebooks applicables
                    </label>
                    <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                        @forelse($ebooks as $ebook)
                        <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                            <input type="checkbox" name="applicable_ebooks[]" value="{{ $ebook->id }}"
                                   {{ in_array($ebook->id, old('applicable_ebooks', [])) ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $ebook->title }}</span>
                        </label>
                        @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aucun ebook disponible</p>
                        @endforelse
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Laissez vide pour appliquer à tous les ebooks</p>
                </div>

                <!-- Applicable Categories -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        Catégories applicables
                    </label>
                    <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                        @forelse($categories as $category)
                        <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                            <input type="checkbox" name="applicable_categories[]" value="{{ $category->id }}"
                                   {{ in_array($category->id, old('applicable_categories', [])) ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                        </label>
                        @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aucune catégorie disponible</p>
                        @endforelse
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Laissez vide pour appliquer à toutes les catégories</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.coupons.index') }}" 
               class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Annuler
            </a>
            <button type="submit" 
                    class="px-8 py-3 bg-gradient-to-r from-primary to-primary-light text-white rounded-xl hover:from-primary-dark hover:to-primary-dark transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="ri-save-line mr-2"></i>Créer le coupon
            </button>
        </div>
    </form>
</div>

<script>
// Update value suffix based on type
document.getElementById('type').addEventListener('change', function() {
    const valueSuffix = document.getElementById('value-suffix');
    const valueInput = document.getElementById('value');
    
    if (this.value === 'percentage') {
        valueSuffix.textContent = '%';
        valueInput.placeholder = '20';
    } else if (this.value === 'fixed') {
        valueSuffix.textContent = '€';
        valueInput.placeholder = '10.00';
    }
});

// Generate random coupon code
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    for (let i = 0; i < 8; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('code').value = result;
}
</script>
@endsection 