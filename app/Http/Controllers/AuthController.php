<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * showLoginForm
     *
     * @return View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * login
     *
     * @return View
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $ip = $request->ip();
            $device = $request->userAgent();

            if ($user->session_id && $user->session_id !== Session::get('user_session-id')) {
                Auth::logout();
                $request->session()->invalidate();

                return back()->withErrors(['email' => 'Akun ini sedang aktif di perangkat lain. Silahkan logout dari perangkat tersebut terlebih dahulu !',])
                    ->onlyInput('email');
            }

            $user->updateloginInfo($ip, $device);

            Session::put('user_session_id', $user->session_id);
            Session::put('user_ip', $ip);
            Session::put('user_device', $user->current_device);

            $request->session()->regenerate();

            if (auth()->user()->role === 'admin') {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('kasir.index');
            }
        }
        return back()->withErrors([
            'email' => 'Email Atau Password Salah',
        ])->onlyInput('email');
    }

    /**
     * logout
     *
     * @return View
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        logger('=== Berfore Logout ===');
        logger('User ID' . ($user ? $user->id : 'null'));
        logger('Session ID' . ($user ? $user->session_id : 'null'));
        logger('Session form session:' . Session::get('user_session_id'));

        if ($user) {
            $user->clearSession();

            $user->refresh();
            logger('=== AFTER CLEAR SESSION ===');
            logger('Session ID After Clear: ' . $user->session_id);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        logger('=== AFTER LOGOUT ===');
        logger('Auth check: ' . (Auth::check() ? 'true' : 'false'));
        return redirect()->route('login');
    }
}
