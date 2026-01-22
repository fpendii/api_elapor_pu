<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserControllerAdmin extends Controller
{
    // LIHAT USER
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // TAMBAH USER
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:users,nik',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'nomor_wa' => 'nullable',
            'foto_ktp' => 'nullable|image|max:2048',
            'role' => 'required',
        ]);

        $fotoKtp = null;
        if ($request->hasFile('foto_ktp')) {
            $fotoKtp = $request->file('foto_ktp')->store('ktp', 'public');
        }

        User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'nomor_wa' => $request->nomor_wa,
            'foto_ktp' => $fotoKtp,
            'role' => $request->role,
            'verifikasi' => 1,
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    // HAPUS USER
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->foto_ktp) {
            Storage::disk('public')->delete($user->foto_ktp);
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}
