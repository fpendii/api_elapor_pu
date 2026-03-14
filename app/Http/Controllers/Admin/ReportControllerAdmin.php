<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\ReportComment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

    public function updateJenisDpa(Request $request, $id, $rabId)
    {
        $report = Report::findOrFail($id);
        $userName = auth()->user()->name;

        // Ambil data lama untuk log komentar
        $oldRab = $report->jenisRab->nama_rab ?? 'Belum Ditentukan';

        // Proses Update Kolom jenis_rab_id
        $report->jenis_rab_id = $rabId;
        $report->save();

        // Ambil data baru setelah di-save
        $report->load('jenisRab');
        $newRab = $report->jenisRab->nama_rab ?? 'Tidak Diketahui';

        // Buat riwayat perubahan
        ReportComment::create([
            'report_id' => $id,
            'user_id' => auth()->id(),
            'pesan' => "Sistem: Jenis DPA diubah dari [{$oldRab}] menjadi [{$newRab}] oleh {$userName}"
        ]);

        return back()->with('success', 'Jenis DPA berhasil diperbarui!');
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

    public function updateAnggaran(Request $request, $id)
    {
        // Bersihkan titik dari input (Contoh: "2.000.000" jadi "2000000")
        // Jika input sudah bersih, fungsi ini tidak akan merusak apapun.
        $nominalInput = str_replace('.', '', $request->nominal_rab);

        $request->merge([
            'nominal_rab' => $nominalInput,
        ]);

        $request->validate([
            'nominal_rab' => 'required|numeric|min:0',
            'dokumen_rab' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $report = Report::findOrFail($id);
        $jenisRab = $report->jenisRab;

        if (!$jenisRab) {
            return back()->with('error', 'Jenis DPA belum dipilih!');
        }

        // Gunakan DB Transaction agar jika error saldo tidak kacau
        return DB::transaction(function () use ($request, $report, $jenisRab) {

            // 1. Kembalikan saldo lama (jika ada)
            if ($report->nominal_rab) {
                $jenisRab->increment('dana', $report->nominal_rab);
            }

            // 2. Cek apakah saldo cukup setelah dikembalikan
            if ($jenisRab->dana < $request->nominal_rab) {
                return back()->with('error', 'Saldo tidak cukup!');
            }

            // 3. Potong saldo dengan nominal baru
            $jenisRab->decrement('dana', $request->nominal_rab);

            // 4. Update data laporan
            $report->nominal_rab = $request->nominal_rab;

            if ($request->hasFile('dokumen_rab')) {
                if ($report->dokumen_rab) {
                    Storage::disk('public')->delete($report->dokumen_rab);
                }
                $report->dokumen_rab = $request->file('dokumen_rab')->store('dokumen_rab', 'public');
            }

            $report->save();

            return back()->with('success', 'Anggaran berhasil diupdate!');
        });
    }
}
