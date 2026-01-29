<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validasi Inputan dari Flutter
        $validator = Validator::make($request->all(), [
            'nik'           => 'required|digits:16|unique:users',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:6',
            'jenis_kelamin' => 'required',
            'pekerjaan'     => 'required',
            'alamat'        => 'required',
            'nomor_wa'      => 'required',
            'foto_ktp'      => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            // Ambil semua pesan error, lalu ambil pesan pertama saja agar simpel bagi user
            $errors = $validator->errors()->all();

            return response()->json([
                'status'  => false,
                'message' => $errors[0], // Mengirim: "NIK harus 16 digit" atau "Email sudah terdaftar"
                'errors'  => $validator->errors() // Tetap kirim semua detail jika diperlukan
            ], 422);
        }

        // 2. Proses Simpan Foto KTP ke Storage
        $fotoPath = null;
        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $namaFile = time() . '_' . $request->nik . '.' . $file->getClientOriginalExtension();
            $fotoPath = $file->storeAs('ktp_files', $namaFile, 'public');
        }

        // 3. Simpan Data User ke Database
        $user = User::create([
            'nik'           => $request->nik,
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan'     => $request->pekerjaan,
            'alamat'        => $request->alamat,
            'nomor_wa'      => $request->nomor_wa,
            'foto_ktp'      => $fotoPath,
            'role'          => 'user', // Default sebagai masyarakat
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Registrasi Berhasil! Silakan Login.',
            'data'    => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau Password salah!'
            ], 401);
        }

        // --- LOGIKA TAMBAHAN: AMBIL NOMOR WA ADMIN ---
        // Mencari user pertama yang rolenya 'admin' untuk diambil nomor WA-nya
        $admin = User::where('role', 'admin')->first();
        $adminPhone = $admin ? $admin->nomor_wa : '628123456789'; // fallback jika admin tidak ditemukan

        // Membersihkan nomor WA (menghilangkan tanda +, spasi, atau angka 0 di depan)
        // Agar formatnya pasti 62...
        $formattedAdminPhone = preg_replace('/[^0-9]/', '', $adminPhone);
        if (str_starts_with($formattedAdminPhone, '0')) {
            $formattedAdminPhone = '62' . substr($formattedAdminPhone, 1);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Login Berhasil!',
            'data'    => array_merge($user->toArray(), [
                'admin_contact' => $formattedAdminPhone // Sisipkan nomor admin ke data user
            ]),
        ]);
    }
}
