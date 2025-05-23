<!-- resources/views/auth/reset-password.blade.php -->
@extends('layouts.auth')

@section('auth-title')
    Nouveau mot de passe
@endsection

@section('auth-subtitle')
    Créez un mot de passe sécurisé pour votre compte
@endsection

@section('auth-content')
    <!-- Error Messages -->
    @if ($errors->any())
    <div class="mb-6 p-4 rounded-lg border text-center bg-red-900 text-red-200 border-red-700">
        <ul class="list-none p-0 m-0 space-y-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ url('/reset-password') }}">
        @csrf
        
        <input type="hidden" name="token" value="{{ $token }}">
        
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-white mb-2">
                <i class="ri-mail-line mr-2"></i>Email
            </label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ $email ?? old('email') }}"
                   class="form-input @error('email') border-red-700 @enderror bg-primary-darker" 
                   required 
                   readonly>
        </div>

        <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-white mb-2">
                <i class="ri-lock-line mr-2"></i>Nouveau mot de passe
            </label>
            <div class="relative">
                <input id="password" 
                       type="password" 
                       name="password"
                       class="form-input @error('password') border-red-700 @enderror pr-10"
                       placeholder="••••••••" 
                       required>
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-light hover:text-white focus:outline-none">
                    <i class="ri-eye-line text-lg"></i>
                </button>
            </div>
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                <i class="ri-lock-line mr-2"></i>Confirmer le mot de passe
            </label>
            <div class="relative">
                <input id="password_confirmation" 
                       type="password" 
                       name="password_confirmation"
                       class="form-input pr-10"
                       placeholder="••••••••" 
                       required>
                <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-light hover:text-white focus:outline-none">
                    <i class="ri-eye-line text-lg"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-primary">
            <i class="ri-check-line mr-2"></i>
            Réinitialiser le mot de passe
        </button>
    </form>
@endsection

@section('auth-footer')
    <a href="{{ url('/login') }}" class="link font-medium">
        <i class="ri-arrow-left-line mr-1"></i>Retour à la connexion
    </a>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle function
        const setupPasswordToggle = (inputId, buttonId) => {
            const passwordInput = document.getElementById(inputId);
            const toggleButton = document.getElementById(buttonId);
            const toggleIcon = toggleButton.querySelector('i');
            
            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'text') {
                    toggleIcon.classList.remove('ri-eye-line');
                    toggleIcon.classList.add('ri-eye-off-line');
                } else {
                    toggleIcon.classList.remove('ri-eye-off-line');
                    toggleIcon.classList.add('ri-eye-line');
                }
            });
        };
        
        // Setup both password toggles
        setupPasswordToggle('password', 'togglePassword');
        setupPasswordToggle('password_confirmation', 'toggleConfirmPassword');
    });
</script>