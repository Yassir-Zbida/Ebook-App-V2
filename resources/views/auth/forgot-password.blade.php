<!-- resources/views/auth/forgot-password.blade.php -->
@extends('layouts.auth')

@section('auth-title')
    Mot de passe oublié
@endsection

@section('auth-subtitle')
    Entrez votre email pour recevoir un lien de réinitialisation
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

    <form method="POST" action="{{ url('/forgot-password') }}">
        @csrf
        
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-white mb-2">
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

        <button type="submit" class="btn-primary">
            <i class="ri-send-plane-line mr-2"></i>
            Envoyer le lien
        </button>
    </form>
@endsection

@section('auth-footer')
    <a href="{{ url('/login') }}" class="link font-medium">
        <i class="ri-arrow-left-line mr-1"></i>Retour à la connexion
    </a>
@endsection