<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, '⚠️ Authentication required');
        }

        $user = Auth::user();
        
        // Restringir acceso a usuarios invitados
        if ($user->account_type === 'guest') {
            abort(423, 'Your guest account has limited access. Please register for full features.');
        }
        
        return $next($request);
    }
}
