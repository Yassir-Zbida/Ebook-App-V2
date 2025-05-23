@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Vue d\'ensemble')

@section('content')
<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-primary to-primary-light p-8 rounded-2xl shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-bold text-white mb-3">Bonjour, {{ auth()->user()->name ?? 'Admin' }}! 👋</h2>
                <p class="text-purple-100 text-lg">Bienvenue dans votre tableau de bord. Voici ce qui se passe aujourd'hui.</p>
            </div>
            <div class="mt-6 md:mt-0">
                <div class="flex items-center space-x-4 bg-white/20 backdrop-blur-sm rounded-xl p-6">
                    <i class="ri-vip-crown-line text-yellow-300 text-3xl"></i>
                    <div>
                        <p class="text-white font-medium">Passez à un plan supérieur</p>
                        <p class="text-purple-100 text-sm">pour débloquer des fonctionnalités avancées</p>
                    </div>
                    <button class="ml-6 bg-white text-primary-dark px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-200 shadow-lg">
                        Choisir un plan
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Total des ventes -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-transform duration-200">
            <div class="flex items-start justify-between mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-shopping-cart-2-line text-white text-2xl"></i>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                    <i class="ri-arrow-up-line mr-1"></i> 432
                </span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Total des ventes</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-white mb-1">5 094</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Depuis 732 (7 derniers jours)</p>
        </div>
        
        <!-- Revenus -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-transform duration-200">
            <div class="flex items-start justify-between mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-money-dollar-circle-line text-white text-2xl"></i>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                    <i class="ri-arrow-up-line mr-1"></i> 2,4%
                </span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Revenus</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-white mb-1">387 542 €</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">24 Sep 2024</p>
        </div>
        
        <!-- Utilisateurs actifs -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-transform duration-200">
            <div class="flex items-start justify-between mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-user-line text-white text-2xl"></i>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                    <i class="ri-arrow-up-line mr-1"></i> 345
                </span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Utilisateurs actifs</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-white mb-1">2 412</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Depuis 732 (7 derniers jours)</p>
        </div>
        
        <!-- Taux de conversion -->
        <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow hover:scale-105 transition-transform duration-200">
            <div class="flex items-start justify-between mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ri-percent-line text-white text-2xl"></i>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                    <i class="ri-arrow-down-line mr-1"></i> 0,6%
                </span>
            </div>
            <h3 class="text-gray-600 dark:text-gray-light text-sm font-medium mb-2">Taux de conversion</h3>
            <p class="text-3xl font-bold text-gray-800 dark:text-white mb-1">85%</p>
            <p class="text-sm text-gray-500 dark:text-gray-light">Depuis 732 (7 derniers jours)</p>
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
                <select class="mt-4 sm:mt-0 bg-gray-100 dark:bg-gray-soft border border-gray-200 dark:border-gray-soft rounded-xl px-4 py-3 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                    <option>Année dernière</option>
                    <option>6 derniers mois</option>
                    <option>Mois dernier</option>
                </select>
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
                    <a href="#" class="text-sm font-medium text-primary hover:text-primary-dark transition-colors">Voir tout →</a>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-soft transition-colors">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name=David+Elson&background=8622c7&color=fff" alt="David Elson" class="w-12 h-12 rounded-xl">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-purple-500 rounded-full flex items-center justify-center">
                                <i class="ri-heart-fill text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-800 dark:text-white font-medium">David Elson <span class="font-normal text-gray-600 dark:text-gray-light">a ajouté votre ebook aux favoris</span></p>
                            <p class="text-sm text-gray-500 dark:text-gray-light mt-1">Il y a 6 min</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-soft transition-colors">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name=Kurt+Bates&background=ef4444&color=fff" alt="Kurt Bates" class="w-12 h-12 rounded-xl">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="ri-shopping-cart-fill text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-800 dark:text-white font-medium">Kurt Bates <span class="font-normal text-gray-600 dark:text-gray-light">a acheté votre produit</span></p>
                            <p class="text-sm text-gray-500 dark:text-gray-light mt-1">Il y a 16 min</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-soft transition-colors">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name=Eddie+Lake&background=3b82f6&color=fff" alt="Eddie Lake" class="w-12 h-12 rounded-xl">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-purple-500 rounded-full flex items-center justify-center">
                                <i class="ri-heart-fill text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-800 dark:text-white font-medium">Eddie Lake <span class="font-normal text-gray-600 dark:text-gray-light">a ajouté votre ebook aux favoris</span></p>
                            <p class="text-sm text-gray-500 dark:text-gray-light mt-1">Il y a 20 min</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-soft transition-colors">
                        <div class="relative">
                            <img src="https://ui-avatars.com/api/?name=Patricia+Sanders&background=10b981&color=fff" alt="Patricia Sanders" class="w-12 h-12 rounded-xl">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="ri-shopping-cart-fill text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-800 dark:text-white font-medium">Patricia Sanders <span class="font-normal text-gray-600 dark:text-gray-light">a acheté votre produit</span></p>
                            <p class="text-sm text-gray-500 dark:text-gray-light mt-1">Il y a 32 min</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistiques des produits -->
            <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-6 card-shadow">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Produits</h3>
                    <a href="#" class="text-sm font-medium text-primary hover:text-primary-dark transition-colors">Voir tout →</a>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-soft rounded-xl">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-xl flex items-center justify-center">
                                <i class="ri-check-line text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                            <span class="text-gray-800 dark:text-white font-semibold">Annonces actives</span>
                        </div>
                        <span class="text-3xl font-bold text-gray-800 dark:text-white">6</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-soft rounded-xl">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/20 rounded-xl flex items-center justify-center">
                                <i class="ri-time-line text-yellow-600 dark:text-yellow-400 text-xl"></i>
                            </div>
                            <span class="text-gray-800 dark:text-white font-semibold">Expirées</span>
                        </div>
                        <span class="text-3xl font-bold text-gray-800 dark:text-white">2</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-soft rounded-xl">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-xl flex items-center justify-center">
                                <i class="ri-close-line text-red-600 dark:text-red-400 text-xl"></i>
                            </div>
                            <span class="text-gray-800 dark:text-white font-semibold">Épuisées</span>
                        </div>
                        <span class="text-3xl font-bold text-gray-800 dark:text-white">5</span>
                    </div>
                </div>
                
                <button class="mt-6 w-full bg-gradient-to-r from-primary to-primary-light hover:from-primary-dark hover:to-primary text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="ri-add-line text-lg mr-2"></i> Ajouter un nouvel ebook
                </button>
            </div>
        </div>
    </div>
    
    <!-- Ebooks les plus vendus -->
    <div class="bg-white dark:bg-gray-dark border border-gray-200 dark:border-gray-soft rounded-2xl p-8 card-shadow">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Ebooks les plus vendus</h3>
            <select class="mt-4 sm:mt-0 bg-gray-100 dark:bg-gray-soft border border-gray-200 dark:border-gray-soft rounded-xl px-4 py-3 text-sm text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                <option>Ce mois</option>
                <option>Mois dernier</option>
                <option>Tout le temps</option>
            </select>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b border-gray-200 dark:border-gray-soft">
                        <th class="pb-4 text-sm font-semibold text-gray-600 dark:text-gray-light">Titre du livre</th>
                        <th class="pb-4 text-sm font-semibold text-gray-600 dark:text-gray-light">Auteur</th>
                        <th class="pb-4 text-sm font-semibold text-gray-600 dark:text-gray-light hidden lg:table-cell">Catégorie</th>
                        <th class="pb-4 text-sm font-semibold text-gray-600 dark:text-gray-light">Prix</th>
                        <th class="pb-4 text-sm font-semibold text-gray-600 dark:text-gray-light hidden sm:table-cell">Ventes</th>
                        <th class="pb-4 text-sm font-semibold text-gray-600 dark:text-gray-light">Revenus</th>
                        <th class="pb-4 text-sm font-semibold text-gray-600 dark:text-gray-light">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-soft">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-soft/50 transition-colors">
                        <td class="py-5">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                                    <i class="ri-book-2-fill text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-800 dark:text-white font-semibold">La Révolution Numérique</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-light lg:hidden">Technologie</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 text-gray-600 dark:text-gray-light">Jean Dupont</td>
                        <td class="py-5 text-gray-600 dark:text-gray-light hidden lg:table-cell">Technologie</td>
                        <td class="py-5 text-gray-800 dark:text-white font-semibold">24,99 €</td>
                        <td class="py-5 text-gray-800 dark:text-white hidden sm:table-cell">1 234</td>
                        <td class="py-5 text-green-600 dark:text-green-400 font-bold">30 837,66 €</td>
                        <td class="py-5">
                            <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-soft rounded-lg text-gray-600 dark:text-gray-light hover:text-gray-800 dark:hover:text-white transition-colors">
                                <i class="ri-more-2-fill text-xl"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-soft/50 transition-colors">
                        <td class="py-5">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md">
                                    <i class="ri-book-2-fill text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-800 dark:text-white font-semibold">Maîtriser Python</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-light lg:hidden">Programmation</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 text-gray-600 dark:text-gray-light">Marie Martin</td>
                        <td class="py-5 text-gray-600 dark:text-gray-light hidden lg:table-cell">Programmation</td>
                        <td class="py-5 text-gray-800 dark:text-white font-semibold">34,99 €</td>
                        <td class="py-5 text-gray-800 dark:text-white hidden sm:table-cell">892</td>
                        <td class="py-5 text-green-600 dark:text-green-400 font-bold">31 211,08 €</td>
                        <td class="py-5">
                            <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-soft rounded-lg text-gray-600 dark:text-gray-light hover:text-gray-800 dark:hover:text-white transition-colors">
                                <i class="ri-more-2-fill text-xl"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-soft/50 transition-colors">
                        <td class="py-5">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-md">
                                    <i class="ri-book-2-fill text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-800 dark:text-white font-semibold">Écriture Créative 101</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-light lg:hidden">Écriture</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 text-gray-600 dark:text-gray-light">Emma Bernard</td>
                        <td class="py-5 text-gray-600 dark:text-gray-light hidden lg:table-cell">Écriture</td>
                        <td class="py-5 text-gray-800 dark:text-white font-semibold">19,99 €</td>
                        <td class="py-5 text-gray-800 dark:text-white hidden sm:table-cell">1 567</td>
                        <td class="py-5 text-green-600 dark:text-green-400 font-bold">31 324,33 €</td>
                        <td class="py-5">
                            <button class="p-2 hover:bg-gray-100 dark:hover:bg-gray-soft rounded-lg text-gray-600 dark:text-gray-light hover:text-gray-800 dark:hover:text-white transition-colors">
                                <i class="ri-more-2-fill text-xl"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Revenue Chart with better styling
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Create gradient that works in both light and dark mode
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
                    cornerRadius: 10,
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
    
    observer.observe(document.documentElement, { attributes: true });
</script>
@endpush