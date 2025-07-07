@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
<div class="space-y-8">
    <!-- Welcome Section -->
    <div id="welcome-banner" class="bg-gradient-to-r from-primary to-primary-light p-8 rounded-2xl shadow-xl relative overflow-hidden">
        <!-- Close Button with better positioning -->
        <button id="close-banner" type="button" class="absolute top-4 right-4 text-white p-2 rounded-full focus:outline-none transition-all duration-200 z-20">
            <i class="ri-close-line text-2xl hover:text-white hover:bg-red-700 p-2 rounded-full"></i>
        </button>

        <!-- Background decoration -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>

        <div class="relative z-10">
            <div class="mb-6">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-3">
                    Bonjour, {{ auth()->user()->name ?? 'Admin' }}! 👋
                </h2>
                <p class="text-purple-100 text-lg lg:text-xl max-w-2xl">
                    Bienvenue dans votre tableau de bord. Voici ce qui se passe aujourd'hui.
                </p>
                <div class="mt-4 flex items-center space-x-4 text-purple-100">
                    <div class="flex items-center space-x-2">
                        <i class="ri-calendar-line text-lg"></i>
                        <span>{{ now()->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="ri-time-line text-lg"></i>
                        <span id="current-time">{{ now()->format('H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Total des ventes -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-shopping-cart-2-line text-white text-2xl"></i>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                    <i class="ri-arrow-up-line mr-1"></i> +432
                </span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Total des ventes</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($totalSales) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Croissance: {{ number_format($salesGrowth, 1) }}%</p>
            <div class="mt-4 w-full bg-gray-200 dark:bg-gray-soft rounded-full h-2">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2 rounded-full" style="width: 75%"></div>
            </div>
        </div>

        <!-- Revenus -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-money-dollar-circle-line text-white text-2xl"></i>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                    <i class="ri-arrow-up-line mr-1"></i> +2,4%
                </span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Revenus</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($totalRevenue, 2) }} €</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Croissance: {{ number_format($revenueGrowth, 1) }}%</p>
            <div class="mt-4 w-full bg-gray-200 dark:bg-gray-soft rounded-full h-2">
                <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full" style="width: 85%"></div>
            </div>
        </div>

        <!-- Utilisateurs actifs -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-user-line text-white text-2xl"></i>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                    <i class="ri-arrow-up-line mr-1"></i> +345
                </span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Utilisateurs actifs</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($activeUsers) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Total clients: {{ number_format($totalUsers) }}</p>
            <div class="mt-4 w-full bg-gray-200 dark:bg-gray-soft rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 65%"></div>
            </div>
        </div>

        <!-- Taux de conversion -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
            <div class="flex items-start justify-between mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                    <i class="ri-percent-line text-white text-2xl"></i>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                    <i class="ri-arrow-down-line mr-1"></i> -0,6%
                </span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Taux de conversion</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-white mb-1">{{ number_format($conversionRate, 1) }}%</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Croissance: {{ number_format($conversionGrowth, 1) }}%</p>
            <div class="mt-4 w-full bg-gray-200 dark:bg-gray-soft rounded-full h-2">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2 rounded-full" style="width: 85%"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Revenue Chart -->
        <div class="xl:col-span-2 bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-8 card-shadow">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Aperçu des revenus</h3>
                    <p class="text-gray-600 dark:text-gray-light">Performance mensuelle des revenus</p>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                    <!-- Custom Select -->
                    <div class="relative" x-data="{ open: false, selected: 'Année dernière' }">
                        <button @click="open = !open"
                            class="bg-gray-100 dark:bg-gray-soft border border-gray-200 dark:border-gray-soft rounded-xl px-4 py-3 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer flex items-center justify-between min-w-[140px]">
                            <span x-text="selected"></span>
                            <i class="ri-arrow-down-s-line ml-2 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute top-full left-0 mt-2 w-full bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-xl shadow-lg z-10">
                            <div class="py-2">
                                <button @click="selected = 'Année dernière'; open = false"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-soft transition-colors">
                                    Année dernière
                                </button>
                                <button @click="selected = '6 derniers mois'; open = false"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-soft transition-colors">
                                    6 derniers mois
                                </button>
                                <button @click="selected = 'Mois dernier'; open = false"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-soft transition-colors">
                                    Mois dernier
                                </button>
                            </div>
                        </div>
                    </div>
                    <button class="p-3 bg-gray-100 dark:bg-gray-soft hover:bg-gray-200 dark:hover:bg-gray-medium rounded-xl transition-colors">
                        <i class="ri-download-line text-gray-600 dark:text-gray-light"></i>
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-4xl font-bold text-gray-800 dark:text-white mb-2">14 020 110 €</p>
                <p class="text-gray-600 dark:text-gray-light">Vous avez gagné <span class="text-green-600 dark:text-green-400 font-semibold">+420,00 €</span> ce mois-ci</p>
            </div>

            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- Activités récentes -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Activités récentes</h3>
                    <a href="#" class="text-sm font-medium text-primary hover:text-primary-dark transition-colors flex items-center space-x-1">
                        <span>Voir tout</span>
                        <i class="ri-arrow-right-line"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach($recentPurchases as $purchase)
                    <div class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-soft transition-colors group">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($purchase->user->name) }}&background=8622c7&color=fff" alt="{{ $purchase->user->name }}" class="w-12 h-12 rounded-xl shadow-md">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="ri-shopping-cart-fill text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-800 dark:text-white font-medium">
                                <span class="font-semibold">{{ $purchase->user->name }}</span>
                                <span class="font-normal text-gray-600 dark:text-gray-light">a acheté {{ $purchase->ebook->title }}</span>
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-light mt-1 flex items-center space-x-1">
                                <i class="ri-time-line text-xs"></i>
                                <span>{{ $purchase->purchased_at->diffForHumans() }}</span>
                            </p>
                        </div>
                    </div>
                    @endforeach
                    @foreach($recentReviews as $review)
                    <div class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-soft transition-colors group">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=f59e0b&color=fff" alt="{{ $review->user->name }}" class="w-12 h-12 rounded-xl shadow-md">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="ri-star-fill text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-800 dark:text-white font-medium">
                                <span class="font-semibold">{{ $review->user->name }}</span>
                                <span class="font-normal text-gray-600 dark:text-gray-light">a laissé un avis {{ $review->rating }} étoiles sur {{ $review->ebook->title }}</span>
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-light mt-1 flex items-center space-x-1">
                                <i class="ri-time-line text-xs"></i>
                                <span>{{ $review->created_at->diffForHumans() }}</span>
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Statistiques des produits -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Produits</h3>
                    <a href="#" class="text-sm font-medium text-primary hover:text-primary-dark transition-colors flex items-center space-x-1">
                        <span>Voir tout</span>
                        <i class="ri-arrow-right-line"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl border border-green-200 dark:border-green-800/30">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="ri-check-line text-white text-xl"></i>
                            </div>
                            <span class="text-gray-800 dark:text-white font-semibold">Annonces actives</span>
                        </div>
                        <span class="text-3xl font-bold text-green-600 dark:text-green-400">6</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl border border-yellow-200 dark:border-yellow-800/30">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="ri-time-line text-white text-xl"></i>
                            </div>
                            <span class="text-gray-800 dark:text-white font-semibold">Expirées</span>
                        </div>
                        <span class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">2</span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl border border-red-200 dark:border-red-800/30">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="ri-close-line text-white text-xl"></i>
                            </div>
                            <span class="text-gray-800 dark:text-white font-semibold">Épuisées</span>
                        </div>
                        <span class="text-3xl font-bold text-red-600 dark:text-red-400">5</span>
                    </div>
                </div>

                <button class="mt-6 w-full bg-gradient-to-r from-primary to-primary-light hover:from-primary-dark hover:to-primary text-white font-semibold py-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 flex items-center justify-center space-x-2">
                    <i class="ri-add-line text-lg"></i>
                    <span>Ajouter un nouvel ebook</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Ebooks les plus vendus -->
    <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-8 card-shadow">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Ebooks les plus vendus</h3>
                <p class="text-gray-600 dark:text-gray-light">Performances de vos meilleurs produits</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <!-- Custom Select -->
                <div class="relative" x-data="{ open: false, selected: 'Ce mois' }">
                    <button @click="open = !open"
                        class="bg-gray-100 dark:bg-gray-soft border border-gray-200 dark:border-gray-soft rounded-xl px-4 py-3 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer flex items-center justify-between min-w-[120px]">
                        <span x-text="selected"></span>
                        <i class="ri-arrow-down-s-line ml-2 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open"
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute top-full left-0 mt-2 w-full bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-xl shadow-lg z-10">
                        <div class="py-2">
                            <button @click="selected = 'Ce mois'; open = false"
                                class="w-full text-left px-4 py-2 text-sm text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-soft transition-colors">
                                Ce mois
                            </button>
                            <button @click="selected = 'Mois dernier'; open = false"
                                class="w-full text-left px-4 py-2 text-sm text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-soft transition-colors">
                                Mois dernier
                            </button>
                            <button @click="selected = 'Tout le temps'; open = false"
                                class="w-full text-left px-4 py-2 text-sm text-gray-800 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-soft transition-colors">
                                Tout le temps
                            </button>
                        </div>
                    </div>
                </div>
                <button class="p-3 bg-gray-100 dark:bg-gray-soft hover:bg-gray-200 dark:hover:bg-gray-medium rounded-xl transition-colors">
                    <i class="ri-download-line text-gray-600 dark:text-gray-light"></i>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b border-gray-200 dark:border-gray-soft">
                        <th class="pb-4 text-sm font-semibold text-gray-600 dark:text-gray-light">Titre du livre</th>
                        <th class="pb-4 text-sm font-semibold text-gray-600 dark:text-gray-light">Ventes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-soft">
                    @foreach($topSellingEbooks as $ebook)
                    <tr>
                        <td class="py-5 text-gray-800 dark:text-white font-semibold">{{ $ebook->title }}</td>
                        <td class="py-5 text-gray-800 dark:text-white">{{ $ebook->purchasers_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-600 dark:text-gray-light mb-4 sm:mb-0">
                Affichage de 1 à 5 sur 47 résultats
            </p>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-dark border border-gray-300 dark:border-gray-soft rounded-lg hover:bg-gray-50 dark:hover:bg-gray-soft transition-colors">
                    Précédent
                </button>
                <button class="px-3 py-2 text-sm font-medium text-white bg-primary border border-primary rounded-lg hover:bg-primary-dark transition-colors">
                    1
                </button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-dark border border-gray-300 dark:border-gray-soft rounded-lg hover:bg-gray-50 dark:hover:bg-gray-soft transition-colors">
                    2
                </button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-dark border border-gray-300 dark:border-gray-soft rounded-lg hover:bg-gray-50 dark:hover:bg-gray-soft transition-colors">
                    3
                </button>
                <button class="px-3 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-dark border border-gray-300 dark:border-gray-soft rounded-lg hover:bg-gray-50 dark:hover:bg-gray-soft transition-colors">
                    Suivant
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                    <i class="ri-add-line text-white text-xl"></i>
                </div>
                <div>
                    <h4 class="text-gray-800 dark:text-white font-semibold">Nouvel ebook</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-light">Ajouter un produit</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                    <i class="ri-megaphone-line text-white text-xl"></i>
                </div>
                <div>
                    <h4 class="text-gray-800 dark:text-white font-semibold">Campagne</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-light">Créer une promo</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                    <i class="ri-bar-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h4 class="text-gray-800 dark:text-white font-semibold">Rapport</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-light">Voir les stats</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                    <i class="ri-settings-3-line text-white text-xl"></i>
                </div>
                <div>
                    <h4 class="text-gray-800 dark:text-white font-semibold">Paramètres</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-light">Configurer</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-soft">
        <div class="text-center">
            <p class="text-sm text-gray-500 dark:text-gray-light">
                © {{ date('Y') }} <span class="font-semibold text-primary">Made in China eBooks</span>. Tous droits réservés.
            </p>
        </div>
    </footer>
</div>
@endsection

@push('scripts')
<script>
    // Dynamic time update
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        document.getElementById('current-time').textContent = timeString;
    }

    // Update time immediately and then every second
    updateTime();
    setInterval(updateTime, 1000);

    // Revenue Chart with enhanced styling and dark mode support
    const ctx = document.getElementById('revenueChart').getContext('2d');

    // Create gradient that adapts to dark mode
    function createGradient() {
        const isDarkMode = document.documentElement.classList.contains('dark');
        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        if (isDarkMode) {
            gradient.addColorStop(0, 'rgba(134, 34, 199, 0.4)');
            gradient.addColorStop(1, 'rgba(134, 34, 199, 0)');
        } else {
            gradient.addColorStop(0, 'rgba(134, 34, 199, 0.3)');
            gradient.addColorStop(1, 'rgba(134, 34, 199, 0)');
        }
        return gradient;
    }

    const chartData = {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
        datasets: [{
            label: 'Revenus',
            data: [300000, 320000, 340000, 320000, 350000, 360000, 380000, 370000, 390000, 400000, 380000, 420000],
            borderColor: '#8622c7',
            backgroundColor: createGradient(),
            borderWidth: 4,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#8622c7',
            pointBorderColor: '#fff',
            pointBorderWidth: 3,
            pointRadius: 6,
            pointHoverRadius: 8,
            pointHoverBackgroundColor: '#6315a5',
            pointHoverBorderWidth: 4
        }]
    };

    const chart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#e5e7eb',
                    borderColor: '#8622c7',
                    borderWidth: 2,
                    cornerRadius: 12,
                    padding: 16,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 14
                    },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Revenus: ' + context.parsed.y.toLocaleString('fr-FR') + ' €';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#b3b4b8' : '#6b7280',
                        font: {
                            size: 13,
                            weight: '500'
                        },
                        padding: 10
                    }
                },
                y: {
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? 'rgba(179, 180, 184, 0.1)' : 'rgba(107, 114, 128, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: document.documentElement.classList.contains('dark') ? '#b3b4b8' : '#6b7280',
                        font: {
                            size: 13,
                            weight: '500'
                        },
                        padding: 10,
                        callback: function(value) {
                            return (value / 1000) + 'k €';
                        }
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    // Update chart when dark mode changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                // Update gradient
                chart.data.datasets[0].backgroundColor = createGradient();

                // Update text colors
                chart.options.scales.x.ticks.color = document.documentElement.classList.contains('dark') ? '#b3b4b8' : '#6b7280';
                chart.options.scales.y.ticks.color = document.documentElement.classList.contains('dark') ? '#b3b4b8' : '#6b7280';
                chart.options.scales.y.grid.color = document.documentElement.classList.contains('dark') ? 'rgba(179, 180, 184, 0.1)' : 'rgba(107, 114, 128, 0.1)';

                chart.update();
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true
    });

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K for search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            Alpine.store('searchOpen', true);
        }

        // Ctrl/Cmd + B for sidebar toggle
        if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
            e.preventDefault();
            Alpine.store('sidebarOpen', !Alpine.store('sidebarOpen'));
        }
    });

    // Auto-refresh data every 30 seconds
    setInterval(function() {
        // Here you would typically fetch new data from your API
        console.log('Refreshing dashboard data...');
    }, 30000);


    document.addEventListener('DOMContentLoaded', () => {
        console.log('DOM loaded, initializing banner...');

        const banner = document.getElementById('welcome-banner');
        const closeBtn = document.getElementById('close-banner');

        // Debug: Check if elements exist
        console.log('Banner element:', banner);
        console.log('Close button element:', closeBtn);

        if (!banner) {
            console.error('Banner element not found!');
            return;
        }

        if (!closeBtn) {
            console.error('Close button element not found!');
            return;
        }

        // Hide banner if it was previously closed
        if (localStorage.getItem('dashboardBannerClosed') === 'true') {
            banner.classList.add('hidden');
            console.log('Banner hidden due to previous close action');
        } else {
            console.log('Banner visible (default state)');
        }

        // Add click event listener with debugging
        closeBtn.addEventListener('click', function(e) {
            console.log('Close button clicked!');
            e.preventDefault();
            e.stopPropagation();

            // Add smooth close animation
            banner.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
            banner.style.opacity = '0';
            banner.style.transform = 'translateY(-20px)';

            // Hide banner after animation
            setTimeout(() => {
                banner.classList.add('hidden');
                localStorage.setItem('dashboardBannerClosed', 'true');
                console.log('Banner closed and saved to localStorage');

                // Reset styles
                banner.style.opacity = '';
                banner.style.transform = '';
            }, 300);
        });

        // Test function - call from console: testCloseButton()
        window.testCloseButton = function() {
            console.log('Testing close button...');
            closeBtn.click();
        };

        // Reset function - call from console: resetBanner()
        window.resetBanner = function() {
            localStorage.removeItem('dashboardBannerClosed');
            banner.classList.remove('hidden');
            banner.style.opacity = '';
            banner.style.transform = '';
            console.log('Banner reset - should be visible now');
        };

        console.log('Banner initialization complete');
    });
</script>


@endpush