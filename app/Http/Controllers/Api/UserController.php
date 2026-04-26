<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'nik' => $user->nik, // Tambahkan ini
                'name' => $user->name,
                'email' => $user->email,
                'jenis_kelamin' => $user->jenis_kelamin, // Tambahkan ini
                'pekerjaan' => $user->pekerjaan,
                'nomor_wa' => $user->nomor_wa,
                'alamat' => $user->alamat,
                'foto_ktp' => $user->foto_ktp, // Tambahkan ini
                'role' => $user->role, // Tambahkan ini
                'verifikasi' => $user->verifikasi, // Tambahkan ini
                'created_at' => $user->created_at,
            ],
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->name = $request->name ?? $user->name;
        $user->nik = $request->nik ?? $user->nik;
        $user->nomor_wa = $request->nomor_wa ?? $user->nomor_wa;
        $user->pekerjaan = $request->pekerjaan ?? $user->pekerjaan;
        $user->alamat = $request->alamat ?? $user->alamat;
        $user->jenis_kelamin = $request->jenis_kelamin ?? $user->jenis_kelamin;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if (!$user->isDirty()) {
            return response()->json([
                'status' => 'info',
                'message' => 'Tidak ada perubahan data'
            ], 200);
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui',
            'data' => $user,
        ], 200);
    }
}
