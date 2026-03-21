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

        return redirect()->route('admin.jenis-dap.index')
            ->with('success', 'Jenis DAP berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_rab' => 'required|string|max:255',
            'dana'     => 'required|string',
        ]);

        $jenisRab = JenisRab::findOrFail($id);
        $jenisRab->update($request->all());

        return redirect()->route('admin.jenis-dap.index')
            ->with('success', 'Jenis DAP berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jenisRab = JenisRab::findOrFail($id);
        $jenisRab->delete();

        return redirect()->route('admin.jenis-dap.index')
            ->with('success', 'Jenis DAP berhasil dihapus.');
    }
}
