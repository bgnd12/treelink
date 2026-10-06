<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Redirect already-authenticated users away from guest-only pages
     * (login, register, forgot-password) to their proper dashboard.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                return $user->is_admin
                    ? redirect()->route('admin.index')
                    : redirect()->route('dashboard.index');
            }
        }

        return $next($request);
    }
}
