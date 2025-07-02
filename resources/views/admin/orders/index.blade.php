@extends('layouts.app')

@section('title', 'Gestion des Commandes')
@section('page-title', 'Gestion des Commandes')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-primary to-primary-light p-6 rounded-2xl shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i class="ri-shopping-bag-2-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-white mb-1">Gestion des Commandes</h1>
                    <p class="text-purple-100">Suivez et gérez toutes les commandes de vos clients</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-6">
        <!-- Total Orders -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-shopping-bag-2-line text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Total Commandes</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($stats['total']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Toutes les commandes</p>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-time-line text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">En Attente</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($stats['pending']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Commandes en attente</p>
        </div>

        <!-- Processing Orders -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-loader-4-line text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">En Traitement</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($stats['processing']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Commandes en cours</p>
        </div>

        <!-- Completed Orders -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-check-line text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Terminées</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($stats['completed']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Commandes complétées</p>
        </div>

        <!-- Cancelled Orders -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-close-line text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Annulées</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($stats['cancelled']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Commandes annulées</p>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-money-dollar-circle-line text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Revenus Totaux</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($stats['total_revenue'], 2) }} €</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Chiffre d'affaires</p>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 lg:space-x-4">
            <!-- Search -->
            <div class="flex-1 max-w-md">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ri-search-line text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-input pl-10" placeholder="Rechercher par numéro de commande, client...">
                </form>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3">
                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()" class="form-input">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>En traitement</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminée</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Remboursée</option>
                </select>

                <!-- Payment Status Filter -->
                <select name="payment_status" onchange="this.form.submit()" class="form-input">
                    <option value="">Tous les paiements</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="processing" {{ request('payment_status') === 'processing' ? 'selected' : '' }}>En traitement</option>
                    <option value="completed" {{ request('payment_status') === 'completed' ? 'selected' : '' }}>Terminé</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Échoué</option>
                    <option value="cancelled" {{ request('payment_status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                </select>

                <!-- Date Range -->
                <div class="flex space-x-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="form-input" placeholder="Date début">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="form-input" placeholder="Date fin">
                </div>

                <!-- Apply Filters Button -->
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-primary-light text-white rounded-lg hover:from-primary-dark hover:to-primary-dark transition-all duration-200 flex items-center space-x-2">
                    <i class="ri-filter-line"></i>
                    <span>Filtrer</span>
                </button>

                <!-- Clear Filters -->
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 flex items-center space-x-2">
                    <i class="ri-refresh-line"></i>
                    <span>Réinitialiser</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm overflow-hidden card-shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Commande
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Client
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Montant
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Paiement
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center">
                                    <i class="ri-shopping-bag-2-line text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $order->order_number }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->total_items }} article(s)
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs font-semibold">
                                        {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $order->user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ number_format($order->total_amount, 2) }} €
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="text-xs text-green-600 dark:text-green-400">
                                -{{ number_format($order->discount_amount, 2) }} €
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status_badge_class }}">
                                {{ $order->formatted_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->payment_status_badge_class }}">
                                {{ $order->formatted_payment_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div>{{ $order->created_at->format('d/m/Y') }}</div>
                            <div>{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="viewOrderDetails({{ $order->id }})" 
                                        class="text-primary hover:text-primary-dark transition-colors p-2 hover:bg-primary/10 rounded-lg">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <a href="{{ route('admin.orders.show', $order) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors p-2 hover:bg-blue-50 rounded-lg">
                                    <i class="ri-external-link-line"></i>
                                </a>
                                <button onclick="updateOrderStatus({{ $order->id }})" 
                                        class="text-green-600 hover:text-green-800 transition-colors p-2 hover:bg-green-50 rounded-lg">
                                    <i class="ri-edit-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="ri-shopping-bag-2-line text-3xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucune commande trouvée</h3>
                            <p class="text-gray-600 dark:text-gray-400">Aucune commande ne correspond à vos critères de recherche.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Order Details Modal -->
<div id="orderDetailsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-dark rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div id="orderDetailsContent">
                <!-- Content will be loaded here -->
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
                            <option value="pending">En attente</option>
                            <option value="processing">En traitement</option>
                            <option value="completed">Terminée</option>
                            <option value="cancelled">Annulée</option>
                            <option value="refunded">Remboursée</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes (optionnel)</label>
                        <textarea name="notes" rows="3" class="form-input w-full" placeholder="Ajouter des notes..."></textarea>
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
let currentOrderId = null;

function viewOrderDetails(orderId) {
    fetch(`/admin/orders/${orderId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const order = data.order;
                const modal = document.getElementById('orderDetailsModal');
                const content = document.getElementById('orderDetailsContent');
                
                content.innerHTML = `
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Commande #${order.order_number}
                            </h3>
                            <button onclick="closeOrderDetailsModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="px-6 py-4 max-h-96 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Informations client</h4>
                                <div class="space-y-2">
                                    <p class="text-sm"><span class="font-medium">Nom:</span> ${order.user.name}</p>
                                    <p class="text-sm"><span class="font-medium">Email:</span> ${order.user.email}</p>
                                    <p class="text-sm"><span class="font-medium">Date de commande:</span> ${new Date(order.created_at).toLocaleDateString('fr-FR')}</p>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Informations commande</h4>
                                <div class="space-y-2">
                                    <p class="text-sm"><span class="font-medium">Statut:</span> 
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${getStatusBadgeClass(order.status)}">
                                            ${order.formatted_status}
                                        </span>
                                    </p>
                                    <p class="text-sm"><span class="font-medium">Paiement:</span> 
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${getPaymentStatusBadgeClass(order.payment_status)}">
                                            ${order.formatted_payment_status}
                                        </span>
                                    </p>
                                    <p class="text-sm"><span class="font-medium">Total:</span> ${parseFloat(order.total_amount).toFixed(2)} €</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Articles commandés</h4>
                            <div class="space-y-3">
                                ${order.order_items.map(item => `
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center">
                                            <i class="ri-book-line text-white text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">${item.ebook_title}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Quantité: ${item.quantity}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">${parseFloat(item.subtotal).toFixed(2)} €</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">${parseFloat(item.price).toFixed(2)} € l'unité</p>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
                
                modal.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors du chargement des détails de la commande');
        });
}

function closeOrderDetailsModal() {
    document.getElementById('orderDetailsModal').classList.add('hidden');
}

function updateOrderStatus(orderId) {
    currentOrderId = orderId;
    document.getElementById('updateStatusModal').classList.remove('hidden');
}

function closeUpdateStatusModal() {
    document.getElementById('updateStatusModal').classList.add('hidden');
    currentOrderId = null;
}

document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(`/admin/orders/${currentOrderId}/status`, {
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

function getStatusBadgeClass(status) {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
        'processing': 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
        'completed': 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
        'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
        'refunded': 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400'
    };
    return classes[status] || classes['pending'];
}

function getPaymentStatusBadgeClass(status) {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
        'processing': 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
        'completed': 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
        'failed': 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
        'cancelled': 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400'
    };
    return classes[status] || classes['pending'];
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        closeOrderDetailsModal();
        closeUpdateStatusModal();
    }
});
</script>
@endpush
@endsection 