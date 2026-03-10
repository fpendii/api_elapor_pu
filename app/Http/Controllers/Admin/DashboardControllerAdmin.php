<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Models\Report;

class DashboardControllerAdmin extends Controller
{
    public function index()
    {
   

        // 1. Ambil semua jumlah status milik user ini sekaligus dalam satu query
        // pluck('total', 'status') akan menghasilkan array: ['Proposal' => 5, 'Selesai' => 2, ...]
        $counts = Report::
            selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // 2. Susun statistik untuk widget dashboard
        $stats = [
            // Menghitung total dari semua nilai di dalam koleksi counts
            'total'     => $counts->sum(),

            // Status Awal: Proposal
            'proposal'  => $counts->get('Proposal', 0),

            // Gabungan Status Proses: Verifikasi, Penetapan, Pelaksanaan, Pemeriksaan
            // get('NamaStatus', 0) memastikan jika status tsb tidak ada di DB, nilainya tetap 0 (bukan error)
            'proses'    => ($counts->get('Verifikasi', 0) +
                $counts->get('Penetapan', 0) +
                $counts->get('Pelaksanaan', 0) +
                $counts->get('Pemeriksaan', 0)),

            // Status Akhir: Selesai
            'selesai'   => $counts->get('Selesai', 0),
        ];

        // 3. Ambil 5 laporan terbaru milik user (tanpa filter status agar user lihat progres terbarunya)
        $recent_reports = Report::
            latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recent_reports'));
    }
}
