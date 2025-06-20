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
    <div class="mb-6 p-4 rounded-lg border text-center text-sm bg-red-900 text-red-200 border-red-700">
        <ul class="list-none text-sm p-0 m-0 space-y-1">
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
            Nouveau mot de passe
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
            Confirmer le mot de passe
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
        // Get all form elements
        const form = document.querySelector('form');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        
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
        
        // Validation messages in French
        const messages = {
            email: {
                required: "L'adresse email est obligatoire.",
                invalid: "Veuillez entrer une adresse email valide."
            },
            password: {
                required: "Le nouveau mot de passe est obligatoire.",
                minLength: "Le mot de passe doit contenir au moins 6 caractères."
            },
            passwordConfirmation: {
                required: "La confirmation du mot de passe est obligatoire.",
                noMatch: "Les mots de passe ne correspondent pas."
            }
        };

        // Create error message elements (same style as login form)
        function createErrorElement(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-red-400 text-xs mt-1 error-message';
            errorDiv.textContent = message;
            return errorDiv;
        }

        // Remove existing error message
        function removeError(input) {
            const parent = input.closest('.mb-5, .mb-6') || input.parentElement;
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
            const parent = input.closest('.mb-5, .mb-6') || input.parentElement;
            parent.appendChild(errorElement);
        }
        
        // Validation functions
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Email validation
        function validateEmailField(showErrorMsg = true) {
            const email = emailInput.value.trim();

            // Check if empty
            if (!email) {
                if (showErrorMsg) showError(emailInput, messages.email.required);
                return false;
            }

            // Check email format
            if (!validateEmail(email)) {
                if (showErrorMsg) showError(emailInput, messages.email.invalid);
                return false;
            }

            removeError(emailInput);
            return true;
        }

        // Password validation
        function validatePasswordField(showErrorMsg = true) {
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

        // Password confirmation validation
        function validatePasswordConfirmation(showErrorMsg = true) {
            const password = passwordInput.value;
            const passwordConfirmation = passwordConfirmationInput.value;

            // Check if empty
            if (!passwordConfirmation) {
                if (showErrorMsg) showError(passwordConfirmationInput, messages.passwordConfirmation.required);
                return false;
            }

            // Check if passwords match
            if (password !== passwordConfirmation) {
                if (showErrorMsg) showError(passwordConfirmationInput, messages.passwordConfirmation.noMatch);
                return false;
            }

            removeError(passwordConfirmationInput);
            return true;
        }
        
        // Real-time validation on blur
        emailInput.addEventListener('blur', function() {
            if (this.value.trim()) {
                validateEmailField();
            }
        });

        passwordInput.addEventListener('blur', function() {
            if (this.value) {
                validatePasswordField();
            }
        });

        passwordConfirmationInput.addEventListener('blur', function() {
            if (this.value) {
                validatePasswordConfirmation();
            }
        });

        // Real-time validation on input
        passwordInput.addEventListener('input', function() {
            if (this.classList.contains('border-red-700')) {
                removeError(this);
            }
            // Also check confirmation field if it has a value
            if (passwordConfirmationInput.value && passwordConfirmationInput.classList.contains('border-red-700')) {
                removeError(passwordConfirmationInput);
            }
        });

        passwordConfirmationInput.addEventListener('input', function() {
            if (this.classList.contains('border-red-700')) {
                removeError(this);
            }
        });

        emailInput.addEventListener('input', function() {
            if (this.classList.contains('border-red-700')) {
                removeError(this);
            }
        });
        
        // Form submission validation
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate all fields
            const isEmailValid = validateEmailField();
            const isPasswordValid = validatePasswordField();
            const isPasswordConfirmationValid = validatePasswordConfirmation();

            // If all validations pass, submit the form
            if (isEmailValid && isPasswordValid && isPasswordConfirmationValid) {
                // Show loading state
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="ri-loader-4-line mr-2 animate-spin"></i>Réinitialisation...';
                
                // Submit the form
                this.submit();
                
                // Re-enable button after 10 seconds (fallback)
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }, 10000);
            } else {
                // Focus on first invalid field
                if (!isEmailValid) {
                    emailInput.focus();
                } else if (!isPasswordValid) {
                    passwordInput.focus();
                } else if (!isPasswordConfirmationValid) {
                    passwordConfirmationInput.focus();
                }
            }
        });
    });
</script>

<style>
    /* Add CSS for loader animation */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>
@endpush