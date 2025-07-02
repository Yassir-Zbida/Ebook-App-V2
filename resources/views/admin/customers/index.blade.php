@extends('layouts.app')

@section('title', 'Gestion des Clients')
@section('page-title', 'Gestion des Clients')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-primary to-primary-light p-6 rounded-2xl shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i class="ri-user-line text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-white mb-1">Gestion des Clients</h1>
                    <p class="text-purple-100">Gérez votre base de clients et suivez leurs activités</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-6">
        <!-- Total Customers -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-user-line text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Total Clients</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($stats['total']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Tous les clients</p>
        </div>

        <!-- Active Customers -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-user-check-line text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Clients Actifs</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($stats['active']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Clients actifs</p>
        </div>

        <!-- Inactive Customers -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-user-forbid-line text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Clients Inactifs</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($stats['inactive']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Clients inactifs</p>
        </div>

        <!-- New This Month -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-user-add-line text-white text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Nouveaux ce mois</h3>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($stats['new_this_month']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Nouveaux clients</p>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
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
                <form method="GET" action="{{ route('admin.customers.index') }}" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="ri-search-line text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-input pl-10" placeholder="Rechercher par nom, email...">
                </form>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3">
                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()" class="form-input">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactifs</option>
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
                <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 flex items-center space-x-2">
                    <i class="ri-refresh-line"></i>
                    <span>Réinitialiser</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl shadow-sm overflow-hidden card-shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Client
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Commandes
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Total Dépensé
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Dernière Commande
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-semibold">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $customer->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Client depuis {{ $customer->created_at->format('M Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $customer->email }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $customer->email_verified_at ? 'Vérifié' : 'Non vérifié' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $customer->total_orders }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                commande(s)
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ number_format($customer->total_spent, 2) }} €
                            </div>
                            @if($customer->total_orders > 0)
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ number_format($customer->total_spent / $customer->total_orders, 2) }} € en moyenne
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' }}">
                                {{ $customer->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            @if($customer->last_order)
                            <div>{{ $customer->last_order->created_at->format('d/m/Y') }}</div>
                            <div>{{ $customer->last_order->created_at->format('H:i') }}</div>
                            @else
                            <div class="text-gray-400">Aucune commande</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="viewCustomerDetails({{ $customer->id }})" 
                                        class="text-primary hover:text-primary-dark transition-colors p-2 hover:bg-primary/10 rounded-lg">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <a href="{{ route('admin.customers.show', $customer) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors p-2 hover:bg-blue-50 rounded-lg">
                                    <i class="ri-external-link-line"></i>
                                </a>
                                <button onclick="toggleCustomerStatus({{ $customer->id }}, {{ $customer->is_active ? 'false' : 'true' }})" 
                                        class="text-{{ $customer->is_active ? 'red' : 'green' }}-600 hover:text-{{ $customer->is_active ? 'red' : 'green' }}-800 transition-colors p-2 hover:bg-{{ $customer->is_active ? 'red' : 'green' }}-50 rounded-lg">
                                    <i class="ri-{{ $customer->is_active ? 'user-forbid' : 'user-check' }}-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="ri-user-line text-3xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucun client trouvé</h3>
                            <p class="text-gray-600 dark:text-gray-400">Aucun client ne correspond à vos critères de recherche.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($customers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Customer Details Modal -->
<div id="customerDetailsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-dark rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div id="customerDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewCustomerDetails(customerId) {
    fetch(`/admin/customers/${customerId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const customer = data.customer;
                const stats = data.stats;
                const modal = document.getElementById('customerDetailsModal');
                const content = document.getElementById('customerDetailsContent');
                
                content.innerHTML = `
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                ${customer.name}
                            </h3>
                            <button onclick="closeCustomerDetailsModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="ri-close-line text-xl"></i>
                            </button>
                        </div>
                    </div>
                    <div class="px-6 py-4 max-h-96 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Informations client</h4>
                                <div class="space-y-2">
                                    <p class="text-sm"><span class="font-medium">Nom:</span> ${customer.name}</p>
                                    <p class="text-sm"><span class="font-medium">Email:</span> ${customer.email}</p>
                                    <p class="text-sm"><span class="font-medium">Membre depuis:</span> ${new Date(customer.created_at).toLocaleDateString('fr-FR')}</p>
                                    <p class="text-sm"><span class="font-medium">Statut:</span> 
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${customer.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'}">
                                            ${customer.is_active ? 'Actif' : 'Inactif'}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Statistiques</h4>
                                <div class="space-y-2">
                                    <p class="text-sm"><span class="font-medium">Total commandes:</span> ${stats.total_orders}</p>
                                    <p class="text-sm"><span class="font-medium">Total dépensé:</span> ${parseFloat(stats.total_spent).toFixed(2)} €</p>
                                    <p class="text-sm"><span class="font-medium">Valeur moyenne:</span> ${parseFloat(stats.average_order_value).toFixed(2)} €</p>
                                    ${stats.last_order ? `<p class="text-sm"><span class="font-medium">Dernière commande:</span> ${new Date(stats.last_order.created_at).toLocaleDateString('fr-FR')}</p>` : ''}
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Dernières commandes</h4>
                            <div class="space-y-3">
                                ${customer.orders.slice(0, 5).map(order => `
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-primary to-primary-light rounded-lg flex items-center justify-center">
                                                <i class="ri-shopping-bag-2-line text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">${order.order_number}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">${new Date(order.created_at).toLocaleDateString('fr-FR')}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">${parseFloat(order.total_amount).toFixed(2)} €</p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${getStatusBadgeClass(order.status)}">
                                                ${order.formatted_status}
                                            </span>
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
            alert('Erreur lors du chargement des détails du client');
        });
}

function closeCustomerDetailsModal() {
    document.getElementById('customerDetailsModal').classList.add('hidden');
}

function toggleCustomerStatus(customerId, newStatus) {
    if (!confirm('Êtes-vous sûr de vouloir modifier le statut de ce client ?')) {
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

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        closeCustomerDetailsModal();
    }
});
</script>
@endpush
@endsection 