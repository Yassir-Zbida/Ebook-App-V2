@extends('layouts.auth')

@section('page-title')
Login
@endsection

@section('auth-title')
Bienvenue
@endsection

@section('auth-subtitle')
Connectez-vous à votre compte pour continuer
@endsection

@section('auth-content')
<!-- Error Messages -->
@if ($errors->any())
<div class="mb-4 p-3 rounded-lg border text-center text-sm bg-red-900 text-red-200 border-red-700 text-sm">
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
            Email
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
            Mot de passe
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
            <label for="remember" class="inline-flex items-center cursor-pointer toggle-container">
                <div class="relative">
                    <input type="checkbox" id="remember" name="remember" class="sr-only">
                    <div class="toggle-bg w-10 h-5 rounded-full"></div>
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

        // Enhanced toggle switch with professional behavior
        const toggleCheckbox = document.getElementById('remember');
        const toggleBg = document.querySelector('.toggle-bg');

        toggleCheckbox.addEventListener('change', function() {
            // Add a subtle scale effect when toggling
            toggleBg.style.transform = 'scale(1.05)';
            setTimeout(() => toggleBg.style.transform = 'scale(1)', 150);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Get form elements
        const loginForm = document.querySelector('form');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const submitButton = loginForm.querySelector('button[type="submit"]');

        // Validation messages in French
        const messages = {
            email: {
                required: "L'adresse email est obligatoire.",
                invalid: "Veuillez entrer une adresse email valide."
            },
            password: {
                required: "Le mot de passe est obligatoire.",
                minLength: "Le mot de passe doit contenir au moins 6 caractères."
            }
        };

        // Create error message elements
        function createErrorElement(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-red-400 text-xs mt-1 error-message';
            errorDiv.textContent = message;
            return errorDiv;
        }

        // Remove existing error message
        function removeError(input) {
            const parent = input.closest('.mb-4') || input.parentElement;
            const existingError = parent.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }
            input.classList.remove('border-red-700');
        }

        // Show error message
        function showError(input, message) {
            removeError(input);
            input.classList.add('border-red-700');
            const errorElement = createErrorElement(message);
            const parent = input.closest('.mb-4') || input.parentElement;
            parent.appendChild(errorElement);
        }

        // Email validation
        function validateEmail(showErrorMsg = true) {
            const email = emailInput.value.trim();

            // Check if empty
            if (!email) {
                if (showErrorMsg) showError(emailInput, messages.email.required);
                return false;
            }

            // Check email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                if (showErrorMsg) showError(emailInput, messages.email.invalid);
                return false;
            }

            removeError(emailInput);
            return true;
        }

        // Password validation
        function validatePassword(showErrorMsg = true) {
            const password = passwordInput.value;

            // Check if empty
            if (!password) {
                if (showErrorMsg) showError(passwordInput, messages.password.required);
                return false;
            }

            // Check minimum length
            if (password.length < 6) {
                if (showErrorMsg) showError(passwordInput, messages.password.minLength);
                return false;
            }

            removeError(passwordInput);
            return true;
        }

        // Real-time validation on blur
        emailInput.addEventListener('blur', function() {
            if (this.value.trim()) {
                validateEmail();
            }
        });

        passwordInput.addEventListener('blur', function() {
            if (this.value) {
                validatePassword();
            }
        });

        // Clear error on input
        emailInput.addEventListener('input', function() {
            if (this.classList.contains('border-red-700')) {
                removeError(this);
            }
        });

        passwordInput.addEventListener('input', function() {
            if (this.classList.contains('border-red-700')) {
                removeError(this);
            }
        });

        // Form submission validation
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate all fields
            const isEmailValid = validateEmail();
            const isPasswordValid = validatePassword();

            // If all validations pass, submit the form
            if (isEmailValid && isPasswordValid) {
                // Disable submit button to prevent double submission
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i>Connexion en cours...';

                // Submit the form
                this.submit();
            } else {
                // Focus on first invalid field
                if (!isEmailValid) {
                    emailInput.focus();
                } else if (!isPasswordValid) {
                    passwordInput.focus();
                }
            }
        });

        // Existing password visibility toggle code
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

        // Existing toggle switch code
        const toggleCheckbox = document.getElementById('remember');
        const toggleBg = document.querySelector('.toggle-bg');

        toggleCheckbox.addEventListener('change', function() {
            toggleBg.style.transform = 'scale(1.05)';
            setTimeout(() => toggleBg.style.transform = 'scale(1)', 150);
        });
    });

    // Add CSS for loader animation
    const style = document.createElement('style');
    style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
`;
    document.head.appendChild(style);
</script>

<style>
    /* Professional Toggle Switch Styles */
    .toggle-bg {
        transition: all 0.3s ease;
        position: relative;
        background-color: #4b5563;
        /* Gray when deactivated */
        border: none;
    }

    .toggle-bg:after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        /* Circle starts at the left (OFF position) */
        width: 16px;
        height: 16px;
        background: white;
        border-radius: 50%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
    }

    /* When checked (remember me is ON), background becomes primary color */
    input:checked+.toggle-bg {
        background-color: #8622c7;
        /* Primary purple color when activated */
    }

    /* Move circle to the right when checked */
    input:checked+.toggle-bg:after {
        transform: translateX(20px);
        /* Move circle to the right when ON */
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
    }

    /* Focus state */
    input:focus+.toggle-bg {
        box-shadow: 0 0 0 2px rgba(134, 34, 199, 0.3);
    }

    /* Hover effect */
    .toggle-container:hover .toggle-bg {
        opacity: 0.9;
    }

    /* Active scaling effect */
    .toggle-container:active .toggle-bg {
        transform: scale(0.95);
    }
</style>