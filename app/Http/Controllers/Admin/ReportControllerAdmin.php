<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\ReportComment;

class ReportControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        // 1. Set default status ke 'Semua' atau 'Proposal'
        $status = $request->query('status', 'Semua');

        // 2. Ambil semua laporan
        $baseQuery = Report::with('user');

        // 3. Hitung jumlah per status (Sesuai dengan Alur Baru)
        // Kita ambil semua status yang ada di database sekaligus agar lebih efisien
        $countsFromDb = Report::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // 4. Pastikan semua status memiliki index (biar tidak error di Blade)
        $listStatus = ['Proposal', 'Verifikasi', 'Penetapan', 'Pelaksanaan', 'Pemeriksaan', 'Selesai'];
        $count = [];
        foreach ($listStatus as $s) {
            $count[$s] = $countsFromDb[$s] ?? 0;
        }

        // 5. Filter tabel berdasarkan request
        $query = Report::with('user')->latest();
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


    public function show($id)
    {
        $report = Report::with('user')->findOrFail($id);
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

    public function updateStatus($id, $status)
    {
        $report = Report::findOrFail($id);
        $report->status = $status;
        $report->save();

        // Opsional: Buat komentar otomatis setiap status berubah
        ReportComment::create([
            'report_id' => $id,
            'user_id' => auth()->id(),
            'pesan' => "Sistem: Status laporan diubah menjadi " . $status
        ]);

        return back()->with('success', 'Status berhasil diperbarui!');
    }
}
