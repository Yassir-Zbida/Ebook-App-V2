<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request
     */
    /**
     * Handle a login request
     */
    public function login(Request $request)
    {
        // Validate input with French messages
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // Get credentials
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Check if user exists
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()
                ->withErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.'])
                ->withInput($request->only('email'));
        }

        // Check if user is active
        if (!$user->is_active) {
            return back()
                ->withErrors(['email' => 'Votre compte a été désactivé. Veuillez contacter le support.'])
                ->withInput($request->only('email'));
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            /** @var User $user */
            $user = Auth::user();
            
            // Log successful login
            Log::info('User logged in', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip(),
            ]);
            
            // Set success message based on role
            $message = $user->role === 'admin' ? 'Bienvenue, Administrateur !' : 'Bienvenue !';
            
            // Get redirect URL: intended URL or dashboard
            $redirectUrl = $this->getRedirectUrl();
            
            return redirect($redirectUrl)->with('success', $message);
        }

        // Authentication failed
        return back()
            ->withErrors(['email' => 'Les identifiants fournis sont incorrects.'])
            ->withInput($request->only('email'));
    }

    /**
     * Get the appropriate redirect URL after login
     */
    private function getRedirectUrl(): string
    {
        // Check for intended URL first (from Laravel's intended mechanism)
        $intended = session()->pull('url.intended');
        
        // If there's an intended URL and it's not a login-related page, use it
        if ($intended && $this->isValidRedirectUrl($intended)) {
            return $intended;
        }
        
        // Otherwise, redirect to dashboard (same URL for all users)
        return route('dashboard');
    }

    /**
     * Check if the URL is valid for redirection
     */
    private function isValidRedirectUrl(?string $url): bool
    {
        if (!$url) {
            return false;
        }
        
        // Don't redirect to auth-related pages
        $excludedPaths = ['/login', '/logout', '/forgot-password', '/reset-password'];
        
        foreach ($excludedPaths as $path) {
            if (str_contains($url, $path)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Handle a logout request
     */
    public function logout(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if ($user) {
            // Log logout
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);
        }
        
        // Logout and clear session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a reset link to the given user
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
        ]);

        // Check if user exists and is active
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Aucun utilisateur n\'est associé à cette adresse email.'
            ]);
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Votre compte a été désactivé. Veuillez contacter le support.'
            ]);
        }

        // Send reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            Log::info('Password reset link sent', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);
            
            return back()->with(['status' => 'Un lien de réinitialisation a été envoyé à votre adresse email.']);
        }

        return back()->withErrors(['email' => 'Impossible d\'envoyer le lien de réinitialisation. Veuillez réessayer.']);
    }

    /**
     * Show the reset password form
     */
    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token, 
            'email' => $request->email
        ]);
    }

    /**
     * Reset the given user's password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'token.required' => 'Le jeton de réinitialisation est requis.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
                
                Log::info('Password reset completed', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Votre mot de passe a été réinitialisé avec succès.');
        }

        return back()->withErrors(['email' => 'Impossible de réinitialiser le mot de passe. Veuillez réessayer.']);
    }

    /**
     * Change password form
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.min' => 'Le nouveau mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'Les nouveaux mots de passe ne correspondent pas.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        /** @var User $user */
        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.'
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        Log::info('Password changed', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
        ]);

        return back()->with('success', 'Votre mot de passe a été modifié avec succès.');
    }

    /**
     * Create user account from external purchase
     * This can be called from external ecommerce via POST request
     */
    public function createUserFromPurchase(Request $request)
    {
        // Validate request with French messages
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'ebook_id' => 'required|exists:ebooks,id',
            'purchase_price' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'ebook_id.required' => 'L\'identifiant de l\'ebook est obligatoire.',
            'ebook_id.exists' => 'L\'ebook spécifié n\'existe pas.',
            'purchase_price.required' => 'Le prix d\'achat est obligatoire.',
            'purchase_price.numeric' => 'Le prix d\'achat doit être un nombre.',
            'purchase_price.min' => 'Le prix d\'achat ne peut pas être négatif.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'La validation a échoué',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate random password
            $password = Str::random(12);
            
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Attach purchased ebook
            $user->purchasedEbooks()->attach($request->ebook_id, [
                'purchase_price' => $request->purchase_price,
                'purchased_at' => now(),
            ]);

            // Get ebook details
            $ebook = Ebook::find($request->ebook_id);

            // Send welcome email (implement as needed)
            // Mail::to($user->email)->send(new WelcomeEbookPurchase($user, $password, $ebook));

            // Log user creation
            Log::info('User created from purchase', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ebook_id' => $request->ebook_id,
                'ebook_title' => $ebook->title,
                'purchase_price' => $request->purchase_price,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Compte utilisateur créé avec succès',
                'data' => [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'ebook_title' => $ebook->title,
                    'login_url' => url('/login'),
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create user from purchase', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'ebook_id' => $request->ebook_id,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Impossible de créer le compte utilisateur'
            ], 500);
        }
    }
}