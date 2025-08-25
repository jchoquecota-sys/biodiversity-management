<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class DebugAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Log session and auth information
        Log::info('DebugAuth Middleware', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'session_id' => Session::getId(),
            'is_authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'session_token' => Session::token(),
            'csrf_token' => $request->header('X-CSRF-TOKEN'),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'session_lifetime' => config('session.lifetime'),
            'session_driver' => config('session.driver'),
        ]);

        // Check if user was authenticated but session expired
        if (!Auth::check() && $request->expectsJson()) {
            Log::warning('Authentication lost during AJAX request', [
                'url' => $request->fullUrl(),
                'session_id' => Session::getId(),
            ]);
        }

        return $next($request);
    }
}