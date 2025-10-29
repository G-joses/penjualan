<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;
        logger('User role: ' . $userRole);
        logger('Allowed roles: ' . implode(',', $roles));

        if (!in_array($userRole, $roles)) {
            abort(403, 'Akses Ditolak');
        }

        return $next($request);
    }
}
