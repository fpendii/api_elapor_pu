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
        $status = $request->query('status', 'Semua');
        $listStatus = ['Proposal', 'Verifikasi', 'Penetapan', 'Pelaksanaan', 'Pemeriksaan', 'Selesai'];

        // Ambil counts sekaligus dan isi default 0 untuk status yang kosong
        $countsFromDb = Report::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $count = collect($listStatus)->mapWithKeys(fn($s) => [
            $s => $countsFromDb->get($s, 0)
        ])->toArray();

        // Query data laporan
        $reports = Report::with('user')
            ->when($status !== 'Semua', fn($q) => $q->where('status', $status))
            ->latest()
            ->get();

        return view('admin.laporan.index', compact('reports', 'status', 'count'));
    }


    public function show($id)
    {
        $report = Report::with('user')->findOrFail($id);
        $allJenisRab = \App\Models\JenisRab::all();
        return view('admin.laporan.show', compact('report', 'allJenisRab'));
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

    public function updateStatus(Request $request, $id, $value)
    {
        $report = Report::findOrFail($id);
        $userName = auth()->user()->name; // Opsional: untuk memperjelas siapa yang mengubah

        // 1. JIKA UPDATE PRIORITAS
        if ($request->type === 'prioritas') {
            $oldPrioritas = $report->prioritas ?? 'Tidak Ada';
            $report->prioritas = $value;
            $report->save();

            ReportComment::create([
                'report_id' => $id,
                'user_id' => auth()->id(),
                'pesan' => "Sistem: Prioritas laporan diubah dari [{$oldPrioritas}] menjadi [{$value}] oleh {$userName}"
            ]);

            return back()->with('success', 'Prioritas berhasil diperbarui!');
        }

        // 2. JIKA UPDATE KATEGORI (JENIS RAB)
        if ($request->type === 'kategori') {
            // Ambil data RAB lama dan baru untuk catatan komentar
            $oldRab = $report->jenisRab->nama_rab ?? 'Belum Ditentukan';

            $report->jenis_rab_id = $value; // $value di sini adalah ID Jenis RAB
            $report->save();

            // Load relasi terbaru untuk mendapatkan nama RAB yang baru
            $report->load('jenisRab');
            $newRab = $report->jenisRab->nama_rab ?? 'Tidak Diketahui';

            ReportComment::create([
                'report_id' => $id,
                'user_id' => auth()->id(),
                'pesan' => "Sistem: Kategori RAB diubah dari [{$oldRab}] menjadi [{$newRab}] oleh {$userName}"
            ]);

            return back()->with('success', 'Kategori RAB berhasil diperbarui!');
        }

        // 3. DEFAULT: UPDATE STATUS LAPORAN
        $oldStatus = $report->status;
        $report->status = $value;
        $report->save();

        ReportComment::create([
            'report_id' => $id,
            'user_id' => auth()->id(),
            'pesan' => "Sistem: Status laporan diubah dari [{$oldStatus}] menjadi [{$value}] oleh {$userName}"
        ]);

        return back()->with('success', 'Status berhasil diperbarui!');
    }
}
