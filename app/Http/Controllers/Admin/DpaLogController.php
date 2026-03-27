<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenggunaanRabLog;
use App\Models\JenisRab;
use Illuminate\Http\Request;

class DpaLogController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar semua Jenis DPA untuk filter di view (opsional)
        $allDpa = JenisRab::all();

        // Query log dengan eager loading agar tidak berat (N+1 Problem)
        $query = PenggunaanRabLog::with(['jenisRab'])->latest();

        // Fitur Filter: Jika admin ingin melihat log DPA tertentu saja
        if ($request->has('dpa_id') && $request->dpa_id != '') {
            $query->where('jenis_rab_id', $request->dpa_id);
        }

        $logs = $query->paginate(20);

        return view('admin.log_dpa.log_dpa', compact('logs', 'allDpa'));
    }

    /**
     * Jika ingin melihat detail 1 log saja (Opsional)
     */
    public function show($id)
    {
        $log = PenggunaanRabLog::with('jenisRab')->findOrFail($id);
        return view('admin.log_dpa.log_dpa_detail', compact('log'));
    }
}