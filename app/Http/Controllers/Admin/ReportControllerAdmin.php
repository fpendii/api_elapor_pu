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
            'pesan' => "Sistem: Data Kategori kegiatan diubah dari [{$oldRab}] menjadi [{$newRab}] oleh {$userName}",
        ]);

        return back()->with('success', 'Dana Kategori Kegiatan Berhasil Diperbarui!');
    }

    public function updateStatus(Request $request, $id, $value)
    {
        $report = Report::findOrFail($id);
        $userName = auth()->user()->name;

        // ... (Logika prioritas dan kategori tetap sama)

        // 3. DEFAULT: UPDATE STATUS LAPORAN
        $oldStatus = $report->status;

        // JIKA STATUS DIUBAH MENJADI SELESAI
        if ($value === 'Selesai' && $oldStatus !== 'Selesai') {

            // Pastikan nominal dan kategori sudah diisi
            if (!$report->jenis_rab_id || $report->nominal_rab <= 0) {
                return back()->with('error', 'Gagal! Kategori kegiatan atau Nominal Anggaran belum ditentukan.');
            }

            return DB::transaction(function () use ($report, $value, $oldStatus, $userName) {
                $jenisRab = \App\Models\JenisRab::lockForUpdate()->find($report->jenis_rab_id);

                // Cek saldo cukup atau tidak
                if ($jenisRab->dana < $report->nominal_rab) {
                    return back()->with('error', 'Saldo dana ' . $jenisRab->nama_rab . ' tidak mencukupi untuk menyelesaikan laporan ini!');
                }

                // A. Potong Saldo Master
                $saldoAwal = $jenisRab->dana;
                $jenisRab->decrement('dana', $report->nominal_rab);
                $saldoAkhir = $jenisRab->dana;

                // B. Update Status Laporan
                $report->status = $value;
                $report->save();

                // C. Catat ke Log Penggunaan Saldo Master
                $jenisRab->logs()->create([
                    'nominal_penggunaan' => $report->nominal_rab,
                    'saldo_awal' => $saldoAwal,
                    'saldo_akhir' => $saldoAkhir,
                    'keterangan' => "Laporan #{$report->id} dinyatakan Selesai. Saldo terpotong otomatis.",
                ]);

                // D. Tambahkan Komentar
                ReportComment::create([
                    'report_id' => $report->id,
                    'user_id' => auth()->id(),
                    'pesan' => "Sistem: Status laporan diubah menjadi [Selesai]. Saldo [{$jenisRab->nama_rab}] telah dipotong sebesar Rp " . number_format($report->nominal_rab, 0, ',', '.') . " oleh {$userName}",
                ]);

                return back()->with('success', 'Laporan berhasil diselesaikan dan saldo telah dipotong!');
            });
        }

        // Update status normal (jika bukan pindah ke 'Selesai')
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
        // Bersihkan titik format ribuan
        $nominalInput = str_replace('.', '', $request->nominal_rab);
        $request->merge(['nominal_rab' => $nominalInput]);

        $request->validate([
            'nominal_rab' => 'required|numeric|min:0',
            'dokumen_rab' => 'nullable|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        $report = Report::findOrFail($id);
        $oldNominal = $report->nominal_rab ?? 0;
        $newNominal = $request->nominal_rab;
        $userName = auth()->user()->name;

        // Simpan data ke laporan saja
        $report->nominal_rab = $newNominal;

        if ($request->hasFile('dokumen_rab')) {
            if ($report->dokumen_rab) {
                Storage::disk('public')->delete($report->dokumen_rab);
            }
            $report->dokumen_rab = $request->file('dokumen_rab')->store('dokumen_rab', 'public');
        }
        $report->save();

        // Catat ke riwayat komentar
        ReportComment::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'pesan' => "Sistem: Anggaran direncanakan sebesar [Rp " . number_format($newNominal, 0, ',', '.') . "] oleh {$userName}. (Saldo kategori belum berkurang sampai status Selesai)",
        ]);

        return back()->with('success', 'Rencana anggaran berhasil diperbarui!');
    }
}
