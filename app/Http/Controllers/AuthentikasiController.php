<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthentikasiController extends Controller
{
    public function showLogin()
    {
        return view('authentikasi.login');
    }

    public function showRegister()
    {
        return view('authentikasi.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nik'           => 'required|unique:users,nik|digits:16',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'pekerjaan'     => 'required|string',
            'alamat'        => 'required|string',
            'nomor_wa'      => 'required|numeric',
            'foto_ktp'      => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Upload Foto KTP
        $fotoPath = null;
        if ($request->hasFile('foto_ktp')) {
            $fotoPath = $request->file('foto_ktp')->store('foto_ktp', 'public');
        }

        // Simpan Data User
        User::create([
            'nik'           => $request->nik,
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan'     => $request->pekerjaan,
            'alamat'        => $request->alamat,
            'nomor_wa'      => $request->nomor_wa,
            'foto_ktp'      => $fotoPath,
            'role'          => 'masyarakat', // Default role
            'verifikasi'    => false,        // Harus diverifikasi admin dulu
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan tunggu verifikasi admin untuk login.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Ambil nilai checkbox 'remember'. Jika dicentang akan bernilai true.
        $remember = $request->has('remember');

        // Tambahkan variabel $remember ke fungsi attempt
        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->withInput();
        }

        $request->session()->regenerate();
        $user = auth()->user();

        // Cek Verifikasi Admin
        if (!$user->verifikasi) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda belum diverifikasi oleh admin.',
            ]);
        }

        return $user->role === 'admin'
            ? redirect('/admin/dashboard')
            : redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
