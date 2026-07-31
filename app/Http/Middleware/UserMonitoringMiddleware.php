<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMonitoringMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->is_banned) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['email' => 'Your account has been temporarily suspended. Please contact the Admin.']);
            }

            if (!$user->last_seen_at || now()->diffInMinutes($user->last_seen_at) >= 1) {
                $user->last_seen_at = now();
                $user->last_ip = $request->ip();
                $user->saveQuietly(); 
            }
        }

        return $next($request);
    }
}
