<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthentikasiController extends Controller
{
    public function showLogin()
    {
        return view('authentikasi.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->withInput();
        }

        $request->session()->regenerate();

        $user = auth()->user();

        if (!$user->verifikasi) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun belum diverifikasi admin',
            ]);
        }

        return $user->role === 'admin'
            ? redirect('/admin')
            : redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
