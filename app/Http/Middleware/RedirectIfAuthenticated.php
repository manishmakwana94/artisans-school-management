<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Get the authenticated user
            $user = Auth::user();
            // Redirect users to different pages based on their role
            if ($user->role == 'teacher') {
                return redirect()->route('teacher.home');
            }

            if ($user->role == 'admin') {
                return redirect()->route('admin.home');
            }

            // Default redirection for other roles (if needed)
            return redirect()->route('home');
        }

        return $next($request);
    }
}
