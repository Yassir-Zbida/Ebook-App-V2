<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserRoleMiddleware
{
    /**
     * Handle an incoming request.
     * Handles authentication and adds user role to request for easy access
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Store intended URL if it's a GET request and not an AJAX request
            if ($request->method() === 'GET' && !$request->expectsJson()) {
                session(['url.intended' => $request->fullUrl()]);
            }
            
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        /** @var User $user */
        $user = Auth::user();
        
        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Votre compte a été désactivé.');
        }
        
        // Add user role to request for easy access
        $request->merge([
            'user_role' => $user->role,
            'formatted_role' => $user->formatted_role,
            'role_badge_class' => $user->role_badge_class,
            'is_admin' => $user->isAdmin(),
            'is_customer' => $user->isCustomer(),
        ]);

        return $next($request);
    }
}