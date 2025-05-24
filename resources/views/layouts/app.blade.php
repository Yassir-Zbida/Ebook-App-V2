<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Boutique Ebooks') }} - @yield('title', 'Tableau de bord')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- RemixIcon Latest Version -->
    <link href="https://cdn.remixicon.com/releases/v4.6.0/remixicon.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#8622c7',
                            dark: '#6315a5',
                            darker: '#36145a',
                            light: '#a855f7',
                            lighter: '#c084fc',
                        },
                        gray: {
                            light: '#b3b4b8',
                            dark: '#030303',
                            darker: '#000000',
                            medium: '#1a1a1a',
                            soft: '#2a2a2a',
                        },
                    },
                    fontSize: {
                        'xs': '.875rem',
                        'sm': '.925rem',
                        'base': '1rem',
                        'lg': '1.125rem',
                        'xl': '1.25rem',
                        '2xl': '1.5rem',
                        '3xl': '1.875rem',
                        '4xl': '2.25rem',
                    },
                    spacing: {
                        '18': '4.5rem',
                        '88': '22rem',
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        .dark ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        
        ::-webkit-scrollbar-track {
            background: #f3f4f6;
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: #36145a;
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #8622c7;
            border-radius: 5px;
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #6315a5;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #6315a5;
        }
        
        /* Smooth transitions */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
        }
        
        /* Card shadows */
        .card-shadow {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .dark .card-shadow {
            box-shadow: 0 0 20px rgba(134, 34, 199, 0.05);
        }
        
        .card-shadow:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .dark .card-shadow:hover {
            box-shadow: 0 0 30px rgba(134, 34, 199, 0.1);
        }
        
        /* Sidebar animation */
        .sidebar-transition {
            transition: width 400ms cubic-bezier(0.4, 0, 0.2, 1), transform 400ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .main-transition {
            transition: margin-left 400ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Search modal backdrop */
        .search-backdrop {
            backdrop-filter: blur(8px);
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #8622c7 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-darker text-gray-800 dark:text-gray-light" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false', searchOpen: false }" x-init="$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val))">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-72' : 'w-20'" class="sidebar-transition bg-white dark:bg-gray-dark border-r border-gray-200 dark:border-gray-soft flex-shrink-0 fixed h-full z-40">
            <div class="h-full flex flex-col">
                <!-- Logo -->
                <div class="h-20 flex items-center justify-between px-6 border-b border-gray-200 dark:border-gray-soft">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="ri-book-3-fill text-white text-2xl"></i>
                        </div>
                        <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="text-gray-800 dark:text-white font-bold text-xl whitespace-nowrap">Boutique Ebooks</span>
                    </a>
                </div>
                
                <!-- Fold/Unfold Button -->
                <div class="px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center justify-center p-3 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-soft dark:hover:bg-gray-medium text-gray-600 dark:text-gray-light transition-all duration-200">
                        <i :class="sidebarOpen ? 'ri-menu-fold-line' : 'ri-menu-unfold-line'" class="text-xl"></i>
                    </button>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-6 py-2 space-y-2 overflow-y-auto">
                    <!-- Tableau de bord -->
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-soft group {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-primary to-primary-light text-white shadow-lg shadow-primary/30' : 'text-gray-700 dark:text-gray-light' }}">
                        <i class="ri-dashboard-3-line text-xl flex-shrink-0"></i>
                        <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-medium whitespace-nowrap">Tableau de bord</span>
                    </a>
                    
                    <!-- Catalogue -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-soft text-gray-700 dark:text-gray-light">
                            <div class="flex items-center space-x-3">
                                <i class="ri-book-line text-xl flex-shrink-0"></i>
                                <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-medium whitespace-nowrap">Catalogue</span>
                            </div>
                            <i x-show="sidebarOpen" :class="open ? 'rotate-90' : ''" class="ri-arrow-right-s-line transition-transform text-lg"></i>
                        </button>
                        <div x-show="open && sidebarOpen" x-collapse class="mt-2 ml-12 space-y-1">
                            <a href="{{ route('admin.ebooks.index') }}" class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-light hover:text-primary dark:hover:text-primary-light">Tous les ebooks</a>
                            <a href="{{ route('admin.ebooks.create') }}" class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-light hover:text-primary dark:hover:text-primary-light">Ajouter un ebook</a>
                        </div>
                    </div>
                    
                    <!-- Commandes -->
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-soft text-gray-700 dark:text-gray-light">
                        <i class="ri-shopping-cart-line text-xl flex-shrink-0"></i>
                        <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-medium whitespace-nowrap">Commandes</span>
                    </a>
                    
                    <!-- Clients -->
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-soft text-gray-700 dark:text-gray-light">
                        <i class="ri-user-line text-xl flex-shrink-0"></i>
                        <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-medium whitespace-nowrap">Clients</span>
                    </a>
                    
                    <!-- Marketing -->
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-soft text-gray-700 dark:text-gray-light">
                            <div class="flex items-center space-x-3">
                                <i class="ri-megaphone-line text-xl flex-shrink-0"></i>
                                <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-medium whitespace-nowrap">Marketing</span>
                            </div>
                            <i x-show="sidebarOpen" :class="open ? 'rotate-90' : ''" class="ri-arrow-right-s-line transition-transform text-lg"></i>
                        </button>
                        <div x-show="open && sidebarOpen" x-collapse class="mt-2 ml-12 space-y-1">
                            <a href="#" class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-light hover:text-primary dark:hover:text-primary-light">Campagnes</a>
                            <a href="#" class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-light hover:text-primary dark:hover:text-primary-light">Promotions</a>
                            <a href="#" class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-light hover:text-primary dark:hover:text-primary-light">Coupons</a>
                        </div>
                    </div>
                    
                    <!-- Analyses -->
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-soft text-gray-700 dark:text-gray-light">
                        <i class="ri-bar-chart-line text-xl flex-shrink-0"></i>
                        <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-medium whitespace-nowrap">Analyses</span>
                    </a>
                    
                    <!-- Paramètres -->
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-soft text-gray-700 dark:text-gray-light">
                        <i class="ri-settings-3-line text-xl flex-shrink-0"></i>
                        <span x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-medium whitespace-nowrap">Paramètres</span>
                    </a>
                </nav>
                
                <!-- User Menu -->
                <div class="border-t border-gray-200 dark:border-gray-soft p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin' }}&background=8622c7&color=fff" 
                             alt="Avatar" 
                             class="w-12 h-12 rounded-xl flex-shrink-0 ring-2 ring-primary/20">
                        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="overflow-hidden">
                            <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">{{ auth()->user()->name ?? 'Admin User' }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-light truncate">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                        </div>
                    </div>
                    <button x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-gray-100 dark:bg-gray-soft rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-700 dark:text-gray-light hover:text-red-600 dark:hover:text-red-400 transition-all duration-200">
                        <i class="ri-logout-box-line text-lg"></i>
                        <span class="text-sm font-medium">Déconnexion</span>
                    </button>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div :class="sidebarOpen ? 'ml-72' : 'ml-20'" class="main-transition flex-1 flex flex-col min-h-screen">
            <!-- Top Bar -->
            <header class="h-20 bg-white dark:bg-gray-dark border-b border-gray-200 dark:border-gray-soft flex items-center justify-between px-8 sticky top-0 z-30">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">@yield('page-title', 'Tableau de bord')</h1>
                </div>
                
                <div class="flex items-center space-x-6">
                    <!-- Search Button -->
                    <button @click="searchOpen = true" class="relative hidden md:flex items-center space-x-3 bg-gray-100 dark:bg-gray-soft hover:bg-gray-200 dark:hover:bg-gray-medium border border-gray-200 dark:border-gray-soft rounded-xl py-3 px-5 text-sm text-gray-600 dark:text-gray-light transition-all duration-200">
                        <i class="ri-search-line text-lg"></i>
                        <span>Rechercher...</span>
                        <kbd class="ml-12 text-xs bg-white dark:bg-gray-medium px-2.5 py-1 rounded-lg border border-gray-300 dark:border-gray-soft font-medium">⌘K</kbd>
                    </button>
                    
                    <!-- Mobile Search -->
                    <button @click="searchOpen = true" class="md:hidden text-gray-600 dark:text-gray-light hover:text-gray-800 dark:hover:text-white p-2">
                        <i class="ri-search-line text-xl"></i>
                    </button>
                    
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode" class="relative p-3 rounded-xl bg-gray-100 dark:bg-gray-soft hover:bg-gray-200 dark:hover:bg-gray-medium text-gray-600 dark:text-gray-light hover:text-gray-800 dark:hover:text-white transition-all duration-200">
                        <i x-show="!darkMode" class="ri-moon-line text-xl"></i>
                        <i x-show="darkMode" class="ri-sun-line text-xl"></i>
                    </button>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 p-6 lg:p-8 overflow-y-auto bg-gray-50 dark:bg-gray-darker">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Search Modal -->
    <div x-show="searchOpen" 
         x-trap.inert.noscroll="searchOpen"
         @keydown.escape.window="searchOpen = false"
         @keydown.cmd.k.window.prevent="searchOpen = true"
         @keydown.ctrl.k.window.prevent="searchOpen = true"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-start justify-center min-h-screen pt-24 px-4">
            <div @click="searchOpen = false" class="fixed inset-0 bg-gray-900/60 dark:bg-gray-900/80 search-backdrop transition-opacity"></div>
            
            <div x-show="searchOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white dark:bg-gray-dark rounded-2xl shadow-2xl w-full max-w-2xl">
                
                <div class="p-6">
                    <div class="relative">
                        <i class="ri-search-line absolute left-5 top-4 text-gray-400 dark:text-gray-light text-xl"></i>
                        <input type="text" 
                               placeholder="Rechercher des livres, auteurs, commandes..." 
                               class="w-full bg-gray-100 dark:bg-gray-soft border-0 rounded-xl py-4 pl-14 pr-5 text-gray-800 dark:text-white placeholder-gray-500 dark:placeholder-gray-light focus:outline-none focus:ring-2 focus:ring-primary text-base"
                               x-init="$el.focus()">
                    </div>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-soft px-6 py-4">
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-light">
                        <span>Tapez pour rechercher</span>
                        <div class="flex items-center space-x-4">
                            <span class="flex items-center space-x-1">
                                <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-soft rounded-lg font-medium">↵</kbd>
                                <span>Sélectionner</span>
                            </span>
                            <span class="flex items-center space-x-1">
                                <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-soft rounded-lg font-medium">ESC</kbd>
                                <span>Fermer</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>