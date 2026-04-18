<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisRab;

class JenisRabController extends Controller
{
    public function index()
    {
        $jenisRabs = JenisRab::all();
        return view('admin.jenis_rab.index', compact('jenisRabs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rab' => 'required|string|max:255',
            'dana'     => 'required|string', // Varchar sesuai permintaanmu
        ]);

        JenisRab::create($request->all());

        return redirect()->route('admin.kategori-kegiatan.index')
            ->with('success', 'Jenis DAP berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nama_rab' => 'required|string|max:255',
        'dana'     => 'required', // Validasi string dulu karena ada kemungkinan titik terbawa
    ]);

    // 1. Ambil data nama_rab
    $data = $request->only('nama_rab');

    // 2. Bersihkan karakter non-digit (titik) dari input dana
    // preg_replace('/[^0-9]/', '', ...) akan menghapus semua selain angka
    $data['dana'] = preg_replace('/[^0-9]/', '', $request->dana);

    // 3. Cari data dan update dengan array $data yang sudah bersih
    $jenisRab = JenisRab::findOrFail($id);
    $jenisRab->update($data);

    return redirect()->route('admin.kategori-kegiatan.index')
        ->with('success', 'Jenis DAP berhasil diperbarui.');
}

    public function destroy($id)
    {
        $jenisRab = JenisRab::findOrFail($id);
        $jenisRab->delete();

        return redirect()->route('admin.kategori-kegiatan.index')
            ->with('success', 'Jenis DAP berhasil dihapus.');
    }
}
