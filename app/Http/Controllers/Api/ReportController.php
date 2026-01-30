<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\GeminiService; // 1. Import Service AI
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    protected $aiService;

    // 2. Inject GeminiService melalui Constructor
    public function __construct(GeminiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        $reports = Report::with(['comments.user'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $reports
        ], 200);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'user_id'         => 'required',
            'kategori'        => 'required',
            'judul'           => 'required',
            'lokasi'          => 'required',
            'deskripsi'       => 'required',
            'foto_kerusakan'  => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Simpan Foto
        $fotoPath = null;
        if ($request->hasFile('foto_kerusakan')) {
            $file = $request->file('foto_kerusakan');
            $namaFile = time() . '_report.' . $file->getClientOriginalExtension();
            $fotoPath = $file->storeAs('reports', $namaFile, 'public');
        }

        // 3. Simpan ke Database (Awal)
        $report = Report::create([
            'user_id'        => $request->user_id,
            'kategori'       => $request->kategori,
            'judul'          => $request->judul,
            'lokasi'         => $request->lokasi,
            'deskripsi'      => $request->deskripsi,
            'foto_kerusakan' => $fotoPath,
            'status'         => 'Menunggu',
        ]);

        // 4. ANALISIS AI (Otomatis setelah save)
        try {
            if ($fotoPath) {
                $aiResult = $this->aiService->analyzeDamage($fotoPath);

                if ($aiResult) {
                    $report->update([
                        'ai_analysis'    => $aiResult['analysis'] ?? null,
                        'ai_damage_type' => $aiResult['type'] ?? null,
                        'ai_severity'    => $aiResult['severity'] ?? null,
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Kita bungkus try-catch agar jika AI gagal/limit, laporan tetap tersimpan
            Log::error("Gagal Analisis AI: " . $e->getMessage());
        }

        return response()->json([
            'status' => true,
            'message' => 'Laporan berhasil dikirim dan dianalisis oleh AI!',
            'data' => $report->fresh() // Mengambil data terbaru setelah update AI
        ], 201);
    }
}
