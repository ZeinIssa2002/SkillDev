<?php

namespace App\Http\Middleware;

use App\Models\Instructor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InstructorMiddleware
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
        
        if (Auth::user()->account_type !== 'instructor') {
            abort(403, '⚠️ You are not instructor');
        }

        $instructor = Instructor::where('account_id', Auth::id())->first();

        if (!$instructor) {
            abort(403, '⚠️ You are not instructor!');
        }

        if (!$instructor->confirmation) {
            abort(403, '⏳ Your instructor account is under review, please wait for activation');
        }
        
        return $next($request);
    }
}
