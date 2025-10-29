<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class CheckSingleSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user) {
            $sessionId = $request->session()->get('user_session_id');

            // ✅ AUTO CLEAR EXPIRED SESSION SETIAP REQUEST (12 jam)
            // Gunakan method getLastActivity() yang sudah diperbaiki
            $lastActivity = $user->getLastActivity();

            if ($lastActivity && $lastActivity->diffInHours(now()) > 12) {
                $user->clearSession();
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Session telah expired. Silakan login kembali.');
            }

            // Check if session ID valid
            if (!$user->isValidSession($sessionId)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Akun ini sedang digunakan di perangkat lain. Silakan login kembali.');
            }

            // ✅ UPDATE LAST ACTIVITY SETIAP REQUEST
            $user->updateLastActivity();
        }

        return $next($request);
    }
}
