@extends('layouts.app')

@section('title', 'Détails de la Commande')
@section('page-title', 'Détails de la Commande')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-primary to-primary-light p-6 rounded-2xl shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="ri-shopping-bag-2-line text-2xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-white mb-1">Commande #{{ $order->order_number }}</h1>
                        <p class="text-purple-100">Détails complets de la commande</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="px-4 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-all duration-200 flex items-center space-x-2">
                        <i class="ri-arrow-left-line"></i>
                        <span>Retour</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Order Details -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Order Information -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-information-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Informations de la commande</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Détails généraux de la commande</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Numéro de commande</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Date de commande</label>
                            <p class="text-gray-900 dark:text-white">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        @if($order->completed_at)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Date de finalisation</label>
                            <p class="text-gray-900 dark:text-white">{{ $order->completed_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Méthode de paiement</label>
                            <p class="text-gray-900 dark:text-white">{{ $order->payment_method ?? 'Non spécifiée' }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Statut de la commande</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->status_badge_class }}">
                                {{ $order->formatted_status }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Statut du paiement</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->payment_status_badge_class }}">
                                {{ $order->formatted_payment_status }}
                            </span>
                        </div>
                        @if($order->coupon_code)
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Code promo utilisé</label>
                            <p class="text-gray-900 dark:text-white">{{ $order->coupon_code }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nombre d'articles</label>
                            <p class="text-gray-900 dark:text-white">{{ $order->total_items }} article(s)</p>
                        </div>
                    </div>
                </div>

                @if($order->notes)
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                    <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">{{ $order->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Order Items -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-shopping-cart-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Articles commandés</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Détail des produits achetés</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center shadow-lg">
                            <i class="ri-book-line text-white text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->ebook_title }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-light">Quantité: {{ $item->quantity }}</p>
                            @if($item->ebook)
                            <p class="text-xs text-gray-500 dark:text-gray-400">ISBN: {{ $item->ebook->id }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($item->subtotal, 2) }} €</p>
                            <p class="text-sm text-gray-600 dark:text-gray-light">{{ number_format($item->price, 2) }} € l'unité</p>
                            @if($item->discount_amount > 0)
                            <p class="text-xs text-green-600 dark:text-green-400">-{{ number_format($item->discount_amount, 2) }} €</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Billing Information -->
            @if($order->billing_info)
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-bank-card-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Informations de facturation</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Détails de facturation du client</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($order->billing_info as $key => $value)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                        </label>
                        <p class="text-gray-900 dark:text-white">{{ $value }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-user-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Client</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Informations du client</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">
                                {{ strtoupper(substr($order->user->name, 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order->user->name }}</h4>
                            <p class="text-gray-600 dark:text-gray-light">{{ $order->user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600 dark:text-gray-light">Total commandes</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $order->user->total_orders }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-light">Total dépensé</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ number_format($order->user->total_spent, 2) }} €</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="{{ route('admin.customers.show', $order->user) }}" 
                           class="w-full px-4 py-2 bg-gradient-to-r from-primary to-primary-light text-white rounded-lg hover:from-primary-dark hover:to-primary-dark transition-all duration-200 flex items-center justify-center space-x-2">
                            <i class="ri-external-link-line"></i>
                            <span>Voir le profil client</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-calculator-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Résumé financier</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Détail des montants</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-light">Sous-total</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($order->subtotal, 2) }} €</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-light">Remise</span>
                        <span class="font-semibold text-green-600 dark:text-green-400">-{{ number_format($order->discount_amount, 2) }} €</span>
                    </div>
                    @endif
                    
                    @if($order->tax_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-light">Taxes</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($order->tax_amount, 2) }} €</span>
                    </div>
                    @endif
                    
                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">Total</span>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($order->total_amount, 2) }} €</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-settings-3-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Actions</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Gérer cette commande</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <button onclick="updateOrderStatus({{ $order->id }})" 
                            class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 flex items-center justify-center space-x-2">
                        <i class="ri-edit-line"></i>
                        <span>Modifier le statut</span>
                    </button>
                    
                    <button onclick="printOrder()" 
                            class="w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 flex items-center justify-center space-x-2">
                        <i class="ri-printer-line"></i>
                        <span>Imprimer</span>
                    </button>
                    
                    <button onclick="exportOrder()" 
                            class="w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all duration-200 flex items-center justify-center space-x-2">
                        <i class="ri-download-line"></i>
                        <span>Exporter PDF</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-dark rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Mettre à jour le statut</h3>
            </div>
            <form id="updateStatusForm" class="px-6 py-4">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nouveau statut</label>
                        <select name="status" class="form-input w-full">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>En traitement</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Terminée</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Remboursée</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes (optionnel)</label>
                        <textarea name="notes" rows="3" class="form-input w-full" placeholder="Ajouter des notes...">{{ $order->notes }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeUpdateStatusModal()" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-primary-light text-white rounded-lg hover:from-primary-dark hover:to-primary-dark transition-all duration-200">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateOrderStatus(orderId) {
    document.getElementById('updateStatusModal').classList.remove('hidden');
}

function closeUpdateStatusModal() {
    document.getElementById('updateStatusModal').classList.add('hidden');
}

document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/admin/orders/${orderId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            status: formData.get('status'),
            notes: formData.get('notes')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeUpdateStatusModal();
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour du statut');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour du statut');
    });
});

function printOrder() {
    window.print();
}

function exportOrder() {
    // Implementation for PDF export
    alert('Fonctionnalité d\'export PDF à implémenter');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        closeUpdateStatusModal();
    }
});
</script>
@endpush
@endsection 