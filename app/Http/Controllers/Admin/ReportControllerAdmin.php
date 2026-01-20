<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;

class ReportControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'Menunggu');

        $query = Report::with('user')->latest();

        // Hitung jumlah per status (UNTUK CARD)
        $count = [
            'Menunggu' => (clone $query)->where('status', 'Menunggu')->count(),
            'Proses'   => (clone $query)->where('status', 'Proses')->count(),
            'Selesai'  => (clone $query)->where('status', 'Selesai')->count(),
            'Ditolak'  => (clone $query)->where('status', 'Ditolak')->count(),
        ];

        // Filter tabel
        if ($status !== 'Semua') {
            $query->where('status', $status);
        }

        $reports = $query->get();

        return view('admin.laporan.index', compact(
            'reports',
            'status',
            'count'
        ));
    }


    public function show(Report $report)
    {
        // dd($report);
        return view('admin.laporan.show', compact('report'));
    }

    public function terima(Report $report)
    {
        $report->update([
            'status' => 'Proses'
        ]);

        return redirect()
            ->route('admin.laporan.show', $report->id)
            ->with('success', 'Laporan diterima dan diproses');
    }

    public function tolak(Report $report)
    {
        $report->update([
            'status' => 'Ditolak'
        ]);

        return redirect()
            ->route('admin.laporan.show', $report->id)
            ->with('success', 'Laporan ditolak');
    }

    public function selesai(Report $report)
    {
        $report->update([
            'status' => 'Selesai'
        ]);

        return redirect()
            ->route('admin.laporan.show', $report->id)
            ->with('success', 'Laporan selesai diproses');
    }
}
