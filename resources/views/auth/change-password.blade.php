<!-- resources/views/auth/change-password.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-dark py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-gray-dark border border-primary-darker rounded-2xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-white mb-2">Changer le mot de passe</h2>
                <p class="text-gray-light">Mettez à jour votre mot de passe</p>
            </div>
            
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
            
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg border text-center bg-green-900 text-green-200 border-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/change-password') }}">
                @csrf
                
                <div class="mb-5">
                    <label for="current_password" class="block text-sm font-medium text-white mb-2">
                        <i class="ri-lock-line mr-2"></i>Mot de passe actuel
                    </label>
                    <input id="current_password" 
                           type="password" 
                           name="current_password"
                           class="form-input @error('current_password') border-red-700 @enderror"
                           placeholder="••••••••" 
                           required>
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-white mb-2">
                        <i class="ri-lock-line mr-2"></i>Nouveau mot de passe
                    </label>
                    <input id="password" 
                           type="password" 
                           name="password"
                           class="form-input @error('password') border-red-700 @enderror"
                           placeholder="••••••••" 
                           required>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                        <i class="ri-lock-line mr-2"></i>Confirmer le nouveau mot de passe
                    </label>
                    <input id="password_confirmation" 
                           type="password" 
                           name="password_confirmation"
                           class="form-input"
                           placeholder="••••••••" 
                           required>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="ri-check-line mr-2"></i>
                    Changer le mot de passe
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="{{ url('/dashboard') }}" class="link font-medium">
                    <i class="ri-arrow-left-line mr-1"></i>Retour au dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection