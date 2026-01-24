<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // PENTING: Cek apakah sudah login
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt login
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // SELALU redirect ke dashboard (bukan halaman terakhir)
            return redirect()->route('admin.dashboard');
        }

        // Login gagal
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        // Clear semua session data
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // PENTING: Clear intended URL
        $request->session()->forget('url.intended');
        
        return redirect()->route('admin.login')->with('success', 'Logout berhasil!');
    }
}