<?php

namespace App\Http\Middleware;

use App\Models\User; // Add this import
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserRoleMiddleware
{
    /**
     * Handle an incoming request.
     * Simply adds user role to request for easy access
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            
            // Add user role to request for easy access
            $request->merge([
                'user_role' => $user->role,
                'formatted_role' => $user->formatted_role,
                'role_badge_class' => $user->role_badge_class,
                'is_admin' => $user->isAdmin(),
                'is_customer' => $user->isCustomer(),
            ]);
        } else {
            $request->merge([
                'user_role' => null,
                'formatted_role' => null,
                'role_badge_class' => null,
                'is_admin' => false,
                'is_customer' => false,
            ]);
        }

        return $next($request);
    }
}