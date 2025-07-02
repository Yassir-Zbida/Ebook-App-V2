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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- RemixIcon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
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
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            200: '#e5e7eb',
                            300: '#d1d5db',
                            400: '#9ca3af',
                            500: '#6b7280',
                            600: '#4b5563',
                            700: '#374151',
                            800: '#1f2937',
                            900: '#111827',
                            950: '#030712',
                            light: '#b3b4b8',
                            dark: '#030303',
                            darker: '#000000',
                            medium: '#1a1a1a',
                            soft: '#2a2a2a',
                        },
                        sidebar: {
                            bg: '#ffffff',
                            'bg-dark': '#030303',
                            border: '#e5e7eb',
                            'border-dark': '#2a2a2a',
                            text: '#374151',
                            'text-dark': '#b3b4b8',
                            'text-muted': '#6b7280',
                            'text-muted-dark': '#9ca3af',
                            hover: '#f3f4f6',
                            'hover-dark': '#2a2a2a',
                            active: '#8622c7',
                            'active-bg': '#f3f4f6',
                            'active-bg-dark': '#2a2a2a',
                        }
                    },
                    animation: {
                        'slide-down': 'slideDown 0.3s ease-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'fade-in': 'fadeIn 0.2s ease-out',
                        'scale-in': 'scaleIn 0.2s ease-out',
                    },
                    keyframes: {
                        slideDown: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(-10px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                        slideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(10px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        scaleIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'scale(0.95)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'scale(1)'
                            },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: rgba(134, 34, 199, 0.5);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.7);
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: rgba(134, 34, 199, 0.7);
        }

        /* Smooth transitions */
        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Sidebar animations */
        .sidebar-enter {
            transform: translateX(-100%);
            opacity: 0;
        }

        .sidebar-enter-active {
            transform: translateX(0);
            opacity: 1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-leave {
            transform: translateX(0);
            opacity: 1;
        }

        .sidebar-leave-active {
            transform: translateX(-100%);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Menu item hover effects */
        .menu-item {
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(134, 34, 199, 0.1), rgba(134, 34, 199, 0.05));
            transition: width 0.3s ease;
            z-index: 0;
        }

        .menu-item:hover::before {
            width: 100%;
        }

        .menu-item>* {
            position: relative;
            z-index: 1;
        }

        /* Active menu item indicator */
        .menu-item-active:hover {
            background-color: transparent;
            border : #8622c7 1px solid; 
            color : #8622c7;
            transition: all 0.3s ease;
        }


        /* Submenu animations */
        .submenu-enter {
            max-height: 0;
            opacity: 0;
        }

        .submenu-enter-active {
            max-height: 500px;
            opacity: 1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .submenu-leave {
            max-height: 500px;
            opacity: 1;
        }

        .submenu-leave-active {
            max-height: 0;
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Modal backdrop blur */
        .modal-backdrop {
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.5);
        }

        /* Floating action button */
        .fab {
            box-shadow: 0 8px 25px rgba(134, 34, 199, 0.3);
        }

        .fab:hover {
            box-shadow: 0 12px 35px rgba(134, 34, 199, 0.4);
            transform: translateY(-2px);
        }

        /* Mobile responsive adjustments */
        @media (max-width: 1024px) {
            /* body {
                overflow-x: hidden;
            } */
        }

        @media (max-width: 768px) {
            .sidebar-mobile {
                width: 320px;
                max-width: 85vw;
            }
        }

        @media (max-width: 640px) {
            .sidebar-mobile {
                width: 100vw;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-darker text-gray-900 dark:text-gray-light"
    x-data="{ 
          sidebarOpen: window.innerWidth >= 1024 ? (localStorage.getItem('sidebarOpen') !== 'false') : false, 
          searchOpen: false,
          adminMenuOpen: false,
          activeSubmenu: null,
          isMobile: window.innerWidth < 1024
      }"
    x-init="
          $watch('sidebarOpen', val => {
              if (!isMobile) localStorage.setItem('sidebarOpen', val)
          });
          window.addEventListener('resize', () => {
              isMobile = window.innerWidth < 1024;
              if (isMobile) sidebarOpen = false;
          });
      "
    @click.away="if (isMobile && sidebarOpen) sidebarOpen = false">

    <div class="min-h-screen flex">

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen && isMobile"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
            style="display: none;"></div>

        <!-- Modern Sidebar -->
        <aside x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform -translate-x-full"
            :class="isMobile ? 'fixed inset-y-0 left-0 z-50 w-80 sm:w-72' : 'fixed inset-y-0 left-0 z-30 w-72'"
            class="fixed inset-y-0 left-0 z-50 rounded-tr-2xl rounded-br-2xl w-72 bg-sidebar-bg dark:bg-sidebar-bg-dark border-r border-sidebar-border dark:border-sidebar-border-dark shadow-xl">

            <div class="h-full flex flex-col">

                <!-- Logo Section -->
                <div class="h-16 flex items-center justify-between px-6 py-10 border-b border-sidebar-border dark:border-sidebar-border-dark ">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('assets/images/logo.gif') }}" alt="Logo" class="w-16 h-15">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sidebar-text dark:text-white font-bold text-lg leading-tight">
                                Boutique
                            </span>
                            <span class="text-primary font-medium text-sm leading-tight">
                                Made in China eBooks
                            </span>
                        </div>
                    </a>
                    <!-- Mobile Close Button -->
                    <button x-show="isMobile"
                        @click="sidebarOpen = false"
                        class="lg:hidden p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-all duration-200">
                        <i class="ri-close-line text-lg text-gray-600 dark:text-gray-400"></i>
                    </button>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                        class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl bg-primary text-sidebar-text dark:text-white text-white hover:bg-transparent transition-all duration-200 {{ request()->routeIs('dashboard') ? 'menu-item-active bg-primary' : '' }}">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-dashboard-3-line text-lg"></i>
                        </div>
                        <span class="font-medium">Tableau de bord</span>
                        @if(request()->routeIs('dashboard'))

                        @endif
                    </a>

                    <!-- Catalogue with Enhanced Submenu -->
                    <div x-data="{ open: activeSubmenu === 'catalogue' }"
                        x-init="$watch('open', val => activeSubmenu = val ? 'catalogue' : null)">
                        <button @click="open = !open; activeSubmenu = open ? 'catalogue' : null"
                            class="menu-item w-full flex items-center justify-between px-4 py-3 rounded-xl text-sidebar-text dark:text-sidebar-text-dark hover:bg-sidebar-hover dark:hover:bg-sidebar-hover-dark group transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-5 h-5 flex items-center justify-center">
                                    <i class="ri-book-line text-lg"></i>
                                </div>
                                <span class="font-medium">Catalogue</span>
                            </div>
                            <i :class="open ? 'rotate-90' : ''"
                                class="ri-arrow-right-s-line transition-transform duration-200 text-sidebar-text-muted dark:text-sidebar-text-muted-dark"></i>
                        </button>
                        <div x-show="open"
                            x-collapse
                            class="mt-2 ml-8 space-y-1 border-l-2 border-gray-200 dark:border-gray-700 pl-4">
                            <a href="#" class="flex items-center space-x-3 px-3 py-2 text-sm text-sidebar-text-muted dark:text-sidebar-text-muted-dark hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200 group">
                                <i class="ri-book-open-line text-base group-hover:scale-110 transition-transform"></i>
                                <span>Tous les livres</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 px-3 py-2 text-sm text-sidebar-text-muted dark:text-sidebar-text-muted-dark hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200 group">
                                <i class="ri-folder-line text-base group-hover:scale-110 transition-transform"></i>
                                <span>Catégories</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 px-3 py-2 text-sm text-sidebar-text-muted dark:text-sidebar-text-muted-dark hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200 group">
                                <i class="ri-user-line text-base group-hover:scale-110 transition-transform"></i>
                                <span>Auteurs</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 px-3 py-2 text-sm text-sidebar-text-muted dark:text-sidebar-text-muted-dark hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200 group">
                                <i class="ri-add-line text-base group-hover:scale-110 transition-transform"></i>
                                <span>Ajouter un livre</span>
                            </a>
                        </div>
                    </div>

                    <!-- Commandes -->
                    <a href="{{ route('admin.orders.index') }}"
                        class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-sidebar-text dark:text-sidebar-text-dark hover:bg-sidebar-hover dark:hover:bg-sidebar-hover-dark group transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'menu-item-active bg-primary' : '' }}">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-shopping-bag-2-line text-lg"></i>
                        </div>
                        <span class="font-medium">Commandes</span>
                        <div class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">12</div>
                    </a>

                    <!-- Clients -->
                    <a href="{{ route('admin.customers.index') }}"
                        class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-sidebar-text dark:text-sidebar-text-dark hover:bg-sidebar-hover dark:hover:bg-sidebar-hover-dark group transition-all duration-200 {{ request()->routeIs('admin.customers.*') ? 'menu-item-active bg-primary' : '' }}">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-user-line text-lg"></i>
                        </div>
                        <span class="font-medium">Clients</span>
                    </a>

                    <!-- Coupons -->
                    <a href="{{ route('admin.coupons.index') }}"
                        class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-sidebar-text dark:text-sidebar-text-dark hover:bg-sidebar-hover dark:hover:bg-sidebar-hover-dark group transition-all duration-200 {{ request()->routeIs('admin.coupons.*') ? 'menu-item-active bg-primary' : '' }}">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-ticket-line text-lg"></i>
                        </div>
                        <span class="font-medium">Coupons</span>
                    </a>

                    <!-- Marketing with Enhanced Submenu -->
                    <div x-data="{ open: activeSubmenu === 'marketing' }"
                        x-init="$watch('open', val => activeSubmenu = val ? 'marketing' : null)">
                        <button @click="open = !open; activeSubmenu = open ? 'marketing' : null"
                            class="menu-item w-full flex items-center justify-between px-4 py-3 rounded-xl text-sidebar-text dark:text-sidebar-text-dark hover:bg-sidebar-hover dark:hover:bg-sidebar-hover-dark group transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-5 h-5 flex items-center justify-center">
                                    <i class="ri-megaphone-line text-lg"></i>
                                </div>
                                <span class="font-medium">Marketing</span>
                            </div>
                            <i :class="open ? 'rotate-90' : ''"
                                class="ri-arrow-right-s-line transition-transform duration-200 text-sidebar-text-muted dark:text-sidebar-text-muted-dark"></i>
                        </button>
                        <div x-show="open"
                            x-collapse
                            class="mt-2 ml-8 space-y-1 border-l-2 border-gray-200 dark:border-gray-700 pl-4">
                            <a href="#" class="flex items-center space-x-3 px-3 py-2 text-sm text-sidebar-text-muted dark:text-sidebar-text-muted-dark hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200 group">
                                <i class="ri-mail-line text-base group-hover:scale-110 transition-transform"></i>
                                <span>Campagnes</span>
                            </a>
                            <a href="#" class="flex items-center space-x-3 px-3 py-2 text-sm text-sidebar-text-muted dark:text-sidebar-text-muted-dark hover:text-primary hover:bg-primary/5 rounded-lg transition-all duration-200 group">
                                <i class="ri-percent-line text-base group-hover:scale-110 transition-transform"></i>
                                <span>Promotions</span>
                            </a>

                        </div>
                    </div>

                    <!-- Analyses -->
                    <a href="#"
                        class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-sidebar-text dark:text-sidebar-text-dark hover:bg-sidebar-hover dark:hover:bg-sidebar-hover-dark group transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-bar-chart-line text-lg"></i>
                        </div>
                        <span class="font-medium">Analyses</span>
                    </a>

                    <!-- Divider -->
                    <div class="my-4 border-t border-sidebar-border dark:border-sidebar-border-dark"></div>

                    <!-- Paramètres -->
                    <a href="#"
                        class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-sidebar-text dark:text-sidebar-text-dark hover:bg-sidebar-hover dark:hover:bg-sidebar-hover-dark group transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-settings-3-line text-lg"></i>
                        </div>
                        <span class="font-medium">Paramètres</span>
                    </a>

                    <!-- Support -->
                    <a href="#"
                        class="menu-item flex items-center space-x-3 px-4 py-3 rounded-xl text-sidebar-text dark:text-sidebar-text-dark hover:bg-sidebar-hover dark:hover:bg-sidebar-hover-dark group transition-all duration-200">
                        <div class="w-5 h-5 flex items-center justify-center">
                            <i class="ri-customer-service-line text-lg"></i>
                        </div>
                        <span class="font-medium">Support</span>
                    </a>
                </nav>

                <!-- Enhanced Admin User Section -->
                <div class="border-t border-sidebar-border dark:border-sidebar-border-dark p-4">
                    <div class="relative" x-data="{ open: false }">
                        <!-- Quick Actions -->
                        <div class="mb-3 grid grid-cols-3 gap-2">
                            <button class="p-2 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-lg transition-all duration-200 group">
                                <i class="ri-notification-line text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform"></i>
                            </button>
                            <button class="p-2 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition-all duration-200 group">
                                <i class="ri-message-line text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform"></i>
                            </button>
                            <button class="p-2 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 rounded-lg transition-all duration-200 group">
                                <i class="ri-star-line text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform"></i>
                            </button>
                        </div>
                        <!-- User Info -->
                        <div class="flex items-center space-x-3 p-3 rounded-xl border border-gray-200 dark:border-gray-900">
                            <div class="relative">
                                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Admin' }}&background=8622c7&color=fff"
                                    alt="Avatar"
                                    class="w-10 h-10 rounded-xl shadow-md">
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-gray-900 rounded-full"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-sidebar-text dark:text-sidebar-text-dark truncate">
                                    {{ auth()->user()->name ?? 'Admin User' }}
                                </p>
                                <p class="text-xs text-sidebar-text-muted dark:text-sidebar-text-muted-dark truncate">
                                    {{ auth()->user()->email ?? 'admin@example.com' }}
                                </p>
                            </div>
                            <button @click="adminMenuOpen = true"
                                class="p-2 hover:bg-white/50 dark:hover:bg-black/20 rounded-lg transition-all duration-200 group">
                                <i class="ri-more-2-fill text-sidebar-text-muted dark:text-sidebar-text-muted-dark group-hover:text-primary"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div :class="sidebarOpen && !isMobile ? 'lg:ml-72' : 'ml-0'" class="flex-1 flex flex-col min-h-screen transition-all duration-300">

            <!-- Top Header -->
            <header class="h-16 dark:bg-gray-dark/80 pt-4 backdrop-blur-sm dark:border-gray-soft flex items-center justify-between px-4 lg:px-6 top-0 z-30">
                <div class="flex items-center space-x-4">
                    <!-- Sidebar Toggle -->
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <i x-show="sidebarOpen" class="ri-menu-fold-line text-lg text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100"></i>
                        <i x-show="!sidebarOpen" class="ri-menu-unfold-line text-lg text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100"></i>
                    </button>

                    <!-- Page Title -->
                    <h1 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-gray-100 truncate">
                        @yield('page-title', 'Tableau de bord')
                    </h1>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Search -->
                    <button @click="searchOpen = true"
                        class="hidden md:flex 
               border border-gray-200 dark:border-gray-600 
               items-center space-x-3 px-3 lg:px-4 py-2 
               bg-gray-100 dark:bg-gray-800 
               hover:bg-gray-200 dark:hover:bg-gray-700 
               rounded-xl transition-all duration-200 group">
                        <i class="ri-search-line text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200"></i>
                        <span class="text-sm text-gray-500 dark:text-gray-400 hidden lg:block">Rechercher...</span>
                        <kbd class="px-2 py-1 text-xs bg-white dark:bg-gray-900 
               border border-gray-300 dark:border-gray-600 
               rounded hidden lg:block">
                            <i class="ri-command-line"></i>K
                        </kbd>
                    </button>


                    <!-- Mobile Search -->
                    <button @click="searchOpen = true"
                        class="md:hidden p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-all duration-200">
                        <i class="ri-search-line text-lg text-gray-600 dark:text-gray-400"></i>
                    </button>

                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <i x-show="!darkMode" class="ri-moon-line text-lg text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100"></i>
                        <i x-show="darkMode" class="ri-sun-line text-lg text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100"></i>
                    </button>

                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 bg-gray-50 dark:bg-gray-darker overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Admin Settings Modal -->
    <div x-show="adminMenuOpen"
        x-trap.inert.noscroll="adminMenuOpen"
        @keydown.escape.window="adminMenuOpen = false"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">

        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div @click="adminMenuOpen = false"
                class="fixed inset-0 modal-backdrop transition-opacity"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"></div>

            <!-- Modal -->
            <div x-show="adminMenuOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white dark:bg-gray-dark rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-gray-200 dark:border-gray-soft">

                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center shadow-lg">
                            <i class="ri-user-settings-line text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Paramètres du compte</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Gérez votre profil et préférences</p>
                        </div>
                    </div>
                    <button @click="adminMenuOpen = false"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-all duration-200">
                        <i class="ri-close-line text-lg text-gray-500 dark:text-gray-400"></i>
                    </button>
                </div>

                <!-- Menu Items -->
                <div class="space-y-2">
                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-all duration-200 group">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <i class="ri-user-line text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Profil</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Modifier vos informations</p>
                        </div>
                        <i class="ri-arrow-right-s-line text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300"></i>
                    </a>

                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-all duration-200 group">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <i class="ri-shield-check-line text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Sécurité</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Mot de passe et 2FA</p>
                        </div>
                        <i class="ri-arrow-right-s-line text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300"></i>
                    </a>

                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-all duration-200 group">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <i class="ri-notification-line text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Notifications</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Préférences d'alerte</p>
                        </div>
                        <i class="ri-arrow-right-s-line text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300"></i>
                    </a>

                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-all duration-200 group">
                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                            <i class="ri-palette-line text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Apparence</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Thème et interface</p>
                        </div>
                        <i class="ri-arrow-right-s-line text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300"></i>
                    </a>
                </div>

                <!-- Divider -->
                <div class="my-6 border-t border-gray-200 dark:border-gray-700"></div>

                <!-- Logout Button -->
                <button class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95">
                    <i class="ri-logout-box-line text-lg"></i>
                    <span>Déconnexion</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Search Modal -->
    <div x-show="searchOpen"
        x-trap.inert.noscroll="searchOpen"
        @keydown.escape.window="searchOpen = false"
        @keydown.cmd.k.window.prevent="searchOpen = !searchOpen"
        @keydown.ctrl.k.window.prevent="searchOpen = !searchOpen"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">

        <div class="flex items-start justify-center min-h-screen pt-16 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div @click="searchOpen = false"
                class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"></div>

            <!-- Modal -->
            <div x-show="searchOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white dark:bg-gray-dark rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6 border border-gray-200 dark:border-gray-soft">

                <!-- Search Header -->
                <div class="flex items-center space-x-4 mb-6">
                    <div class="flex-1 relative">
                        <i class="ri-search-line absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg"></i>
                        <input type="text"
                            placeholder="Rechercher des livres, commandes, clients..."
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-soft border border-gray-200 dark:border-gray-soft rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-gray-900 dark:text-gray-light"
                            x-ref="searchInput"
                            x-init="$nextTick(() => $refs.searchInput.focus())">
                    </div>
                    <button @click="searchOpen = false"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-all duration-200">
                        <i class="ri-close-line text-lg text-gray-500 dark:text-gray-400"></i>
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Actions rapides</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button class="flex items-center space-x-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-all duration-200 group text-left">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <i class="ri-add-line text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Ajouter un livre</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Nouveau produit</p>
                            </div>
                        </button>
                        <button class="flex items-center space-x-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-all duration-200 group text-left">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                <i class="ri-user-add-line text-green-600 dark:text-green-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Nouveau client</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ajouter client</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Recent Searches -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Recherches récentes</h3>
                    <div class="space-y-2">
                        <button class="w-full flex items-center space-x-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-all duration-200 group text-left">
                            <i class="ri-history-line text-gray-400"></i>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Commandes en attente</span>
                        </button>
                        <button class="w-full flex items-center space-x-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-all duration-200 group text-left">
                            <i class="ri-history-line text-gray-400"></i>
                            <span class="text-sm text-gray-700 dark:text-gray-300">Livres populaires</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button class="fixed bottom-6 lg:hidden right-6 w-14 h-14 bg-gradient-to-r from-primary to-primary-light hover:from-primary-dark hover:to-primary text-white rounded-2xl fab transition-all duration-300 z-40">
        <i class="ri-add-line text-2xl"></i>
    </button>

    @stack('scripts')

    <script>
        // Enhanced keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Cmd/Ctrl + K for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const alpineData = document.querySelector('[x-data*="searchOpen"]').__x.$data;
                alpineData.searchOpen = !alpineData.searchOpen;
            }

            // Cmd/Ctrl + B for sidebar toggle
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                const alpineData = document.querySelector('[x-data*="sidebarOpen"]').__x.$data;
                if (!alpineData.isMobile) {
                    alpineData.sidebarOpen = !alpineData.sidebarOpen;
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebarData = document.querySelector('[x-data*="sidebarOpen"]').__x.$data;
            const isMobile = window.innerWidth < 1024;

            sidebarData.isMobile = isMobile;

            if (isMobile && sidebarData.sidebarOpen) {
                sidebarData.sidebarOpen = false;
            } else if (!isMobile && localStorage.getItem('sidebarOpen') !== 'false') {
                sidebarData.sidebarOpen = true;
            }
        });

        // Smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';

        // Prevent body scroll when mobile sidebar is open
        document.addEventListener('alpine:init', () => {
            Alpine.effect(() => {
                const sidebarData = document.querySelector('[x-data*="sidebarOpen"]').__x.$data;
                if (sidebarData.isMobile && sidebarData.sidebarOpen) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        });
    </script>
</body>

</html>