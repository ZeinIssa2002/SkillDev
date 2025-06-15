<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
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
        
        if (Auth::user()->account_type !== 'user') {
            abort(403, '⚠️ You are not User');
        }

        $user = User::where('account_id', Auth::id())->first();

        if (!$user) {
            abort(403, '⚠️ You are not User!');
        }
        
        return $next($request);
    }
}
