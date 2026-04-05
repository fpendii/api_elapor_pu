<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportControllerAdmin extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'Semua');
        $listStatus = ['Proposal', 'Verifikasi', 'Penetapan', 'Pelaksanaan', 'Pemeriksaan', 'Selesai'];

        // 1. Ambil counts (Tetap sama)
        $countsFromDb = \App\Models\Report::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $count = collect($listStatus)->mapWithKeys(fn($s) => [
            $s => $countsFromDb->get($s, 0),
        ])->toArray();

        $query = \App\Models\Report::with('user');

        // Terapkan filter status jika bukan 'Semua'
        if ($status !== 'Semua') {
            $query->where('status', $status);
        }

        // 2. Urutkan berdasarkan Prioritas (Darurat, Tinggi, Sedang, Rendah)
        // Lalu urutkan berdasarkan yang terbaru (latest)
        $query->orderByRaw("
        CASE 
            WHEN prioritas = 'Darurat' THEN 1
            WHEN prioritas = 'Tinggi' THEN 2
            WHEN prioritas = 'Sedang' THEN 3
            WHEN prioritas = 'Rendah' THEN 4
            ELSE 5 
        END ASC
    ")->latest();

        // Eksekusi Pagination
        $reports = $query->paginate(10);

        // Tambahkan query string agar pagination tetap membawa filter status
        $reports->appends(['status' => $status]);

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
            'status' => 'Proses',
        ]);

        return redirect()
            ->route('admin.laporan.show', $report->id)
            ->with('success', 'Laporan diterima dan diproses');
    }

    public function tolak(Report $report)
    {
        $report->update([
            'status' => 'Ditolak',
        ]);

        return redirect()
            ->route('admin.laporan.show', $report->id)
            ->with('success', 'Laporan ditolak');
    }

    public function selesai(Report $report)
    {
        $report->update([
            'status' => 'Selesai',
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
            'pesan' => "Sistem: Jenis DPA diubah dari [{$oldRab}] menjadi [{$newRab}] oleh {$userName}",
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
                'pesan' => "Sistem: Prioritas laporan diubah dari [{$oldPrioritas}] menjadi [{$value}] oleh {$userName}",
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
                'pesan' => "Sistem: Kategori RAB diubah dari [{$oldRab}] menjadi [{$newRab}] oleh {$userName}",
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
            'pesan' => "Sistem: Status laporan diubah dari [{$oldStatus}] menjadi [{$value}] oleh {$userName}",
        ]);

        return back()->with('success', 'Status berhasil diperbarui!');
    }

    public function updateAnggaran(Request $request, $id)
    {
        $nominalInput = str_replace('.', '', $request->nominal_rab);

        $request->merge(['nominal_rab' => $nominalInput]);

        $request->validate([
            'nominal_rab' => 'required|numeric|min:0',
            'dokumen_rab' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $report = Report::findOrFail($id);
        $jenisRab = \App\Models\JenisRab::lockForUpdate()->find($report->jenis_rab_id);

        if (! $jenisRab) {
            return back()->with('error', 'Jenis DPA belum dipilih!');
        }

        return DB::transaction(function () use ($request, $report, $jenisRab) {
            $oldNominal = $report->nominal_rab ?? 0;
            $newNominal = $request->nominal_rab;
            $userName = auth()->user()->name;

            // 1. Hitung saldo awal asli sebelum dikembalikan
            $saldoAwalReal = $jenisRab->dana;

            // 2. Kembalikan saldo lama ke master (Reversal)
            if ($oldNominal > 0) {
                $jenisRab->increment('dana', $oldNominal);
            }

            // 3. Cek apakah saldo cukup untuk nominal baru
            if ($jenisRab->dana < $newNominal) {
                return back()->with('error', 'Saldo dana ' . $jenisRab->nama_rab . ' tidak mencukupi!');
            }

            // 4. Potong saldo dengan nominal baru
            $jenisRab->decrement('dana', $newNominal);
            $saldoAkhirReal = $jenisRab->dana;

            // 5. Update data laporan
            $report->nominal_rab = $newNominal;
            if ($request->hasFile('dokumen_rab')) {
                if ($report->dokumen_rab) {
                    Storage::disk('public')->delete($report->dokumen_rab);
                }
                $report->dokumen_rab = $request->file('dokumen_rab')->store('dokumen_rab', 'public');
            }
            $report->save();

            // 6. CATAT KE LOG PENGGUNAAN
            // Kita catat selisihnya (netto) agar log tidak membingungkan
            $selisih = $newNominal - $oldNominal;

            if ($selisih != 0) {
                $jenisRab->logs()->create([
                    'nominal_penggunaan' => $selisih, // Positif jika nambah penggunaan, negatif jika berkurang
                    'saldo_awal' => $saldoAwalReal,
                    'saldo_akhir' => $saldoAkhirReal,
                    'keterangan' => "Update anggaran Laporan #{$report->id}. " .
                        "({$oldNominal} -> {$newNominal}) oleh {$userName}",
                ]);
            }

            // 7. Tambahkan ke Komentar Laporan (Riwayat Sistem)
            ReportComment::create([
                'report_id' => $report->id,
                'user_id' => auth()->id(),
                'pesan' => 'Sistem: Anggaran diupdate dari [Rp ' . number_format($oldNominal) .
                    '] menjadi [Rp ' . number_format($newNominal) . "] oleh {$userName}",
            ]);

            return back()->with('success', 'Anggaran dan Log berhasil diperbarui!');
        });
    }
}
