<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InstructorOrUserMiddleware
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

        // Redirigir administradores al panel de administración
        if ($user->account_type === 'admin') {
            abort(403, 'Please use your admin panel instead');
        }

        // Restringir acceso a usuarios invitados
        if ($user->account_type === 'guest') {
            abort(423, 'Your guest account has limited access. Please register for full features.');
        }

        // Verificar que el usuario sea instructor o usuario regular
        if (!in_array($user->account_type, ['instructor', 'user'])) {
            abort(403, '⚠️ Access restricted to instructors or regular users only');
        }
        
        return $next($request);
    }
}
