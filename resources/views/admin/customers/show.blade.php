@extends('layouts.app')

@section('title', 'Profil Client')
@section('page-title', 'Profil Client')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-primary to-primary-light p-6 rounded-2xl shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <span class="text-white text-xl font-bold">
                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                        </span>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-white mb-1">{{ $customer->name }}</h1>
                        <p class="text-purple-100">{{ $customer->email }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.customers.index') }}" 
                       class="px-4 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-all duration-200 flex items-center space-x-2">
                        <i class="ri-arrow-left-line"></i>
                        <span>Retour</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Customer Information -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Customer Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Orders -->
                <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="ri-shopping-bag-2-line text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $customerStats['total_orders'] }}</h3>
                            <p class="text-gray-600 dark:text-gray-light text-sm">Commandes totales</p>
                        </div>
                    </div>
                </div>

                <!-- Total Spent -->
                <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="ri-money-dollar-circle-line text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($customerStats['total_spent'], 2) }} €</h3>
                            <p class="text-gray-600 dark:text-gray-light text-sm">Total dépensé</p>
                        </div>
                    </div>
                </div>

                <!-- Average Order Value -->
                <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="ri-calculator-line text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($customerStats['average_order_value'], 2) }} €</h3>
                            <p class="text-gray-600 dark:text-gray-light text-sm">Valeur moyenne</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Details -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-user-settings-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Informations du client</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Détails du profil client</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nom complet</label>
                            <p class="text-gray-900 dark:text-white">{{ $customer->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Adresse email</label>
                            <p class="text-gray-900 dark:text-white">{{ $customer->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Statut du compte</label>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-medium {{ $customer->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' }}">
                                {{ $customer->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Membre depuis</label>
                            <p class="text-gray-900 dark:text-white">{{ $customer->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email vérifié</label>
                            <p class="text-gray-900 dark:text-white">{{ $customer->email_verified_at ? 'Oui' : 'Non' }}</p>
                        </div>
                        @if($customerStats['last_order'])
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Dernière commande</label>
                            <p class="text-gray-900 dark:text-white">{{ $customerStats['last_order']->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button onclick="toggleCustomerStatus({{ $customer->id }}, {{ $customer->is_active ? 'false' : 'true' }})" 
                            class="px-4 py-2 bg-gradient-to-r from-{{ $customer->is_active ? 'red' : 'green' }}-500 to-{{ $customer->is_active ? 'red' : 'green' }}-600 text-white rounded-lg hover:from-{{ $customer->is_active ? 'red' : 'green' }}-600 hover:to-{{ $customer->is_active ? 'red' : 'green' }}-700 transition-all duration-200 flex items-center space-x-2">
                        <i class="ri-{{ $customer->is_active ? 'user-forbid' : 'user-check' }}-line"></i>
                        <span>{{ $customer->is_active ? 'Désactiver' : 'Activer' }} le compte</span>
                    </button>
                </div>
            </div>

            <!-- Order History -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="ri-history-line text-white text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Historique des commandes</h3>
                            <p class="text-gray-600 dark:text-gray-light text-sm">Toutes les commandes du client</p>
                        </div>
                    </div>
                </div>

                @if($customer->orders->count() > 0)
                <div class="space-y-4">
                    @foreach($customer->orders as $order)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center">
                                    <i class="ri-shopping-bag-2-line text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-light">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($order->total_amount, 2) }} €</p>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $order->status_badge_class }}">
                                    {{ $order->formatted_status }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-light">Articles:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $order->total_items }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-light">Paiement:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $order->payment_status_badge_class }}">
                                    {{ $order->formatted_payment_status }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-light">Méthode:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $order->payment_method ?? 'Non spécifiée' }}</span>
                            </div>
                        </div>

                        @if($order->orderItems->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Articles commandés:</h5>
                            <div class="space-y-2">
                                @foreach($order->orderItems as $item)
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 bg-gradient-to-br from-gray-400 to-gray-500 rounded flex items-center justify-center">
                                            <i class="ri-book-line text-white text-xs"></i>
                                        </div>
                                        <span class="text-gray-900 dark:text-white">{{ $item->ebook_title }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">(x{{ $item->quantity }})</span>
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ number_format($item->subtotal, 2) }} €</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="px-3 py-1 bg-gradient-to-r from-primary to-primary-light text-white rounded-lg hover:from-primary-dark hover:to-primary-dark transition-all duration-200 text-sm flex items-center space-x-1">
                                <i class="ri-external-link-line"></i>
                                <span>Voir détails</span>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="ri-shopping-bag-2-line text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucune commande</h3>
                    <p class="text-gray-600 dark:text-gray-400">Ce client n'a pas encore passé de commande.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-settings-3-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Actions rapides</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Gérer ce client</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <button onclick="toggleCustomerStatus({{ $customer->id }}, {{ $customer->is_active ? 'false' : 'true' }})" 
                            class="w-full px-4 py-3 bg-gradient-to-r from-{{ $customer->is_active ? 'red' : 'green' }}-500 to-{{ $customer->is_active ? 'red' : 'green' }}-600 text-white rounded-lg hover:from-{{ $customer->is_active ? 'red' : 'green' }}-600 hover:to-{{ $customer->is_active ? 'red' : 'green' }}-700 transition-all duration-200 flex items-center justify-center space-x-2">
                        <i class="ri-{{ $customer->is_active ? 'user-forbid' : 'user-check' }}-line"></i>
                        <span>{{ $customer->is_active ? 'Désactiver' : 'Activer' }} le compte</span>
                    </button>
                    
                    <button onclick="sendEmail()" 
                            class="w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 flex items-center justify-center space-x-2">
                        <i class="ri-mail-line"></i>
                        <span>Envoyer un email</span>
                    </button>
                    
                    <button onclick="exportCustomerData()" 
                            class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 flex items-center justify-center space-x-2">
                        <i class="ri-download-line"></i>
                        <span>Exporter les données</span>
                    </button>
                </div>
            </div>

            <!-- Customer Activity -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="ri-activity-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Activité récente</h3>
                        <p class="text-gray-600 dark:text-gray-light text-sm">Actions du client</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                            <i class="ri-user-line text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Compte créé</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    @if($customer->email_verified_at)
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="ri-mail-check-line text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Email vérifié</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->email_verified_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($customerStats['last_order'])
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="ri-shopping-bag-2-line text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Dernière commande</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customerStats['last_order']->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleCustomerStatus(customerId, newStatus) {
    const action = newStatus ? 'activer' : 'désactiver';
    if (!confirm(`Êtes-vous sûr de vouloir ${action} ce compte client ?`)) {
        return;
    }
    
    fetch(`/admin/customers/${customerId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            is_active: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour du statut');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour du statut');
    });
}

function sendEmail() {
    // Implementation for sending email
    alert('Fonctionnalité d\'envoi d\'email à implémenter');
}

function exportCustomerData() {
    // Implementation for exporting customer data
    alert('Fonctionnalité d\'export à implémenter');
}
</script>
@endpush
@endsection 