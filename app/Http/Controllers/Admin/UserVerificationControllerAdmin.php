<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserVerificationControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'menunggu');

        $query = User::where('role', 'user');

        // hitung per status
        $count = [
            'menunggu'   => (clone $query)->where('verifikasi', 'menunggu')->count(),
            'acc'        => (clone $query)->where('verifikasi', 'acc')->count(),
            'tidak-acc'  => (clone $query)->where('verifikasi', 'tidak-acc')->count(),
        ];

        if ($status !== 'Semua') {
            $query->where('verifikasi', $status);
        }

        $users = $query->latest()->get();

        return view('admin.users.verifikasi.index', compact(
            'users',
            'status',
            'count'
        ));
    }

    public function show(User $user)
    {
        return view('admin.users.verifikasi.show', compact('user'));
    }

    public function acc(User $user)
    {
        $user->update([
            'verifikasi' => 'acc'
        ]);

        return back()->with('success', 'User berhasil diverifikasi');
    }

    public function tolak(User $user)
    {
        $user->update([
            'verifikasi' => 'tidak-acc'
        ]);

        return back()->with('success', 'Verifikasi user ditolak');
    }
}
