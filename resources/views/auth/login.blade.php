
<!-- resources/views/auth/login.blade.php -->
@extends('layouts.auth')

@section('auth-title')
    Bienvenue
@endsection

@section('auth-subtitle')
    Connectez-vous à votre compte pour continuer
@endsection

@section('auth-content')
    <!-- Error Messages -->
    @if ($errors->any())
    <div class="mb-4 p-3 rounded-lg border text-center bg-red-900 text-red-200 border-red-700 text-sm">
        <ul class="list-none p-0 m-0 space-y-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-white mb-1.5">
                <i class="ri-mail-line mr-1"></i>Email
            </label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}"
                   class="form-input @error('email') border-red-700 @enderror" 
                   placeholder="votre@email.com"
                   required 
                   autofocus>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-white mb-1.5">
                <i class="ri-lock-line mr-1"></i>Mot de passe
            </label>
            <div class="relative">
                <input id="password" 
                       type="password" 
                       name="password"
                       class="form-input @error('password') border-red-700 @enderror pr-10" 
                       placeholder="••••••••"
                       required>
                <button type="button" 
                        id="togglePassword" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-light hover:text-white focus:outline-none">
                    <i class="ri-eye-line text-lg" id="passwordIcon"></i>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between mb-5 gap-2">
            <div class="flex items-center flex-shrink-0">
                <label for="remember" class="inline-flex items-center cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" id="remember" name="remember" class="sr-only peer">
                        <div class="toggle-bg w-10 h-5 bg-primary-darker peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                    </div>
                    <span class="ml-2 text-xs text-gray-light whitespace-nowrap">Se souvenir de moi</span>
                </label>
            </div>

            <a href="{{ url('/forgot-password') }}" class="link text-xs font-medium whitespace-nowrap">
                Mot de passe oublié ?
            </a>
        </div>

        <button type="submit" class="btn-primary mb-3">
            <i class="ri-login-box-line mr-2"></i>
            Se connecter
        </button>
        
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password visibility toggle
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const passwordIcon = document.getElementById('passwordIcon');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                passwordIcon.classList.remove('ri-eye-line');
                passwordIcon.classList.add('ri-eye-off-line');
            } else {
                passwordIcon.classList.remove('ri-eye-off-line');
                passwordIcon.classList.add('ri-eye-line');
            }
        });
        
        // Enhanced toggle switch
        const toggleCheckbox = document.getElementById('remember');
        const toggleBg = document.querySelector('.toggle-bg');
        
        toggleCheckbox.addEventListener('change', function() {
            if (this.checked) {
                toggleBg.style.transform = 'scale(1.05)';
                setTimeout(() => toggleBg.style.transform = 'scale(1)', 150);
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .toggle-bg {
        transition: all 0.3s ease;
    }
    
    .toggle-bg:after {
        transition: transform 0.3s ease, background-color 0.3s ease;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
    }
    
    input:focus ~ .toggle-bg {
        box-shadow: 0 0 0 2px rgba(134, 34, 199, 0.3);
    }
</style>
@endpush