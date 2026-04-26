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
        $counts = Report::selectRaw('status, count(*) as total')
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

       $recent_reports = Report::where('status', '!=', 'Selesai')
    ->orderByRaw("
        CASE prioritas
            WHEN 'Darurat' THEN 4
            WHEN 'Tinggi' THEN 3
            WHEN 'Sedang' THEN 2
            
            WHEN 'Rendah' THEN 1
            ELSE 0
        END DESC
    ")
    ->orderBy('created_at', 'asc') // tanggal paling lama
    ->take(7)
    ->get();

        return view('admin.dashboard.index', compact('stats', 'recent_reports'));
    }
}
