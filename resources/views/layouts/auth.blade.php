<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Made in China') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- RemixIcon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
                        },
                        gray: {
                            light: '#b3b4b8',
                            dark: '#030303',
                        },
                    },
                    spacing: {
                        '50': '50px',  // Custom spacing for logo height
                    }
                }
            }
        }
    </script>
    
    <style type="text/tailwindcss">
        @layer base {
            body {
                @apply font-sans bg-gray-dark text-gray-light;
            }
            
            .btn-primary {
                @apply bg-primary hover:bg-primary-dark text-white font-medium py-2.5 px-4 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary-darker focus:ring-opacity-50 w-full;
            }
            
            .form-input {
                @apply w-full border border-primary-darker rounded-lg py-2.5 px-4 text-white placeholder-gray-light bg-transparent focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300;
            }
            
            .link {
                @apply text-primary hover:text-primary-dark transition-colors duration-300;
            }
            
            .fade-in {
                animation: fadeIn 0.8s ease-in-out;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        }
    </style>
    
    <!-- 3D Logo Animation Styles -->
    <style>
        /* Container for 3D perspective */
        .logo-3d-container {
            perspective: 1000px;
            /* This creates the 3D viewing perspective */
        }
        
        /* The rotating logo wrapper */
        .logo-3d-wrapper {
            position: relative;
            display: inline-block;
            transform-style: preserve-3d;
            /* Ensures child elements maintain 3D positioning */
            animation: rotateY360 2s linear infinite;
            /* 4 second rotation, continuous */
        }
        
        /* The actual logo with shadow and lighting effects */
        .logo-3d {
            display: block;
            width: auto;
            height: 120px;
            /* Subtle shadow for depth */
            filter: drop-shadow(0 10px 20px rgba(134, 34, 199, 0.3));
            /* Backface visibility ensures smooth rotation */
            backface-visibility: visible;
            /* Slight upward tilt for better 3D effect */
            transform: rotateX(-5deg);
        }
        
        /* Reflection effect underneath the logo */
        .logo-reflection {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            height: 30%;
            background: linear-gradient(to bottom, 
                rgba(134, 34, 199, 0.1) 0%, 
                transparent 100%);
            transform: scaleY(-1) translateY(-1px);
            opacity: 0.3;
            filter: blur(2px);
            pointer-events: none;
        }
        
        /* Glow effect that pulses */
        .logo-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle, 
                rgba(134, 34, 199, 0.2) 0%, 
                transparent 70%);
            transform: translate(-50%, -50%);
            animation: glow 2s ease-in-out infinite alternate;
            pointer-events: none;
            z-index: -1;
        }
        
        /* Y-axis rotation animation */
        @keyframes rotateY360 {
            0% {
                transform: rotateY(0deg) rotateX(-5deg);
            }
            100% {
                transform: rotateY(360deg) rotateX(-5deg);
            }
        }
        
        /* Subtle glow animation */
        @keyframes glow {
            0% {
                opacity: 0.3;
                transform: translate(-50%, -50%) scale(0.9);
            }
            100% {
                opacity: 0.6;
                transform: translate(-50%, -50%) scale(1.1);
            }
        }
        
        /* Enhanced lighting effect on hover */
        .logo-3d-container:hover .logo-3d-wrapper {
            animation-play-state: paused;
            /* Pause rotation on hover */
        }
        
        .logo-3d-container:hover .logo-3d {
            filter: drop-shadow(0 15px 30px rgba(134, 34, 199, 0.5)) 
                    brightness(1.1);
            /* Brighter and stronger shadow on hover */
            transition: filter 0.3s ease;
        }
        
        /* Loading state - logo fades in with rotation */
        @keyframes logoEntry {
            0% {
                opacity: 0;
                transform: rotateY(180deg) rotateX(-5deg) scale(0.8);
            }
            100% {
                opacity: 1;
                transform: rotateY(360deg) rotateX(-5deg) scale(1);
            }
        }
        
        .logo-3d-wrapper {
            animation: logoEntry 1s ease-out, rotateY360 4s linear 1s infinite;
            /* Entry animation first, then continuous rotation */
        }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased min-h-screen flex items-center justify-center bg-gray-dark">
    <div class="w-full max-w-md mx-auto p-4">
        <!-- 3D Rotating Logo -->
        <div class="flex justify-center mb-6">
            <a href="{{ url('/') }}" class="logo-3d-container">
                <div class="logo-3d-wrapper">
                    <!-- Glow effect behind logo -->
                    <div class="logo-glow"></div>
                    
                    <!-- Main logo -->
                    <img src="{{ asset('assets/images/logo.svg') }}" 
                         alt="Made in China" 
                         class="logo-3d">
                    
                    <!-- Reflection effect -->
                    <div class="logo-reflection"></div>
                </div>
            </a>
        </div>
        
        <!-- Auth Card - Reduced padding -->
        <div class="bg-gray-dark border border-primary-darker rounded-2xl p-6 fade-in">
            <div class="text-center mb-6">
                <h1 class="text-xl font-bold text-white mb-1">@yield('auth-title')</h1>
                <p class="text-gray-light text-sm">@yield('auth-subtitle')</p>
            </div>
            
            <!-- Flash Messages -->
            @if (session('status'))
                <div class="bg-primary-darker border border-primary text-white px-3 py-2 rounded-lg mb-4 text-center text-sm">
                    {{ session('status') }}
                </div>
            @endif
            
            @if (session('success'))
                <div class="bg-green-900 border border-green-700 text-green-200 px-3 py-2 rounded-lg mb-4 text-center text-sm">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-900 border border-red-700 text-red-200 px-3 py-2 rounded-lg mb-4 text-center text-sm">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Auth Content -->
            @yield('auth-content')
            
            <!-- Auth Footer -->
            @if (View::hasSection('auth-footer'))
            <div class="mt-4 text-center text-sm text-gray-light">
                @yield('auth-footer')
            </div>
            @endif
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>