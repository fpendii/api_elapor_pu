<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportImage; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $aiService;

    public function __construct(GeminiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        // Mengambil report beserta foto-fotonya dan komentar
        $reports = Report::with(['images', 'comments.user'])
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
        Log::info($request->all());
        // 1. VALIDASI
        $validator = Validator::make($request->all(), [
            'user_id'          => 'required',
            'jenis_usulan'     => 'required|string', // <--- Tambahkan validasi ini
            'kategori'         => 'required',
            'judul'            => 'required',
            'lokasi'           => 'required',
            'deskripsi'        => 'required',
            'foto_kerusakan'   => 'required',
            'foto_kerusakan.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            // 'prioritas' => 'required'
            'nomer_pelapor'    => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        log::error($request->all());

        DB::beginTransaction();
        try {
            // 2. SIMPAN DATA REPORT
            $report = Report::create([
                'user_id'      => $request->user_id,
                'jenis_usulan' => $request->jenis_usulan, // <--- Simpan datanya di sini
                'kategori'     => $request->kategori,
                'judul'        => $request->judul,
                'lokasi'       => $request->lokasi,
                'deskripsi'    => $request->deskripsi,
                'prioritas'    => $request->prioritas,
                // 'status'       => 'Menunggu',
                'nomer_pelapor' => $request->nomer_pelapor,
            ]);

            // 3. SIMPAN BANYAK FOTO (Kode tetap sama)
            $pathsForAI = [];
            if ($request->hasFile('foto_kerusakan')) {
                foreach ($request->file('foto_kerusakan') as $file) {
                    $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('reports', $namaFile, 'public');

                    ReportImage::create([
                        'report_id' => $report->id,
                        'path'      => $path
                    ]);

                    $pathsForAI[] = $path;
                }
            }

            DB::commit();

            // 4. ANALISIS AI (Tetap menggunakan foto pertama)
            if (!empty($pathsForAI)) {
                $this->analyzeWithAI($report, $pathsForAI[0]);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Laporan berhasil dikirim dengan ' . count($pathsForAI) . ' foto',
                'data'    => $report->load('images')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('STORE REPORT ERROR: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Terjadi kesalahan saat menyimpan laporan'], 500);
        }
    }

    /**
     * Fungsi Helper untuk Analisis AI agar code store() lebih rapi
     */
    private function analyzeWithAI($report, $fotoPath)
    {
        try {
            $apiKey = env('GOOGLE_VISION_KEY');
            $fullPath = storage_path('app/public/' . $fotoPath);

            // Kita ambil mime type secara manual karena ini dari path storage
            $mimeType = mime_content_type($fullPath);

            $prompt = 'Balas HANYA dalam JSON VALID tanpa teks lain.
            Format: {"analysis":"", "damage_type":"", "severity":"ringan|sedang|berat"}';

            $payload = [
                'contents' => [[
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => base64_encode(file_get_contents($fullPath)),
                            ]
                        ]
                    ]
                ]]
            ];

            $response = Http::post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}",
                $payload
            );

            $rawText = $response['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($rawText) {
                $cleanText = trim(preg_replace('/```json|```/i', '', $rawText));
                $aiResult = json_decode($cleanText, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $report->update([
                        'ai_analysis'    => $aiResult['analysis'] ?? null,
                        'ai_damage_type' => $aiResult['damage_type'] ?? null,
                        'ai_severity'    => strtolower($aiResult['severity'] ?? 'ringan'),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('AI ANALYSIS ERROR: ' . $e->getMessage());
        }
    }

    public function getStats()
    {
        try {
            // Menghitung total semua aduan
            $total = \App\Models\Report::count();

            // Menghitung aduan yang statusnya 'proses'
            // Sesuaikan string 'proses' dengan value yang Anda simpan di database
            $proses = \App\Models\Report::where('status', 'proses')->count();

            // Menghitung aduan yang statusnya 'selesai'
            $selesai = \App\Models\Report::where('status', 'selesai')->count();

            return response()->json([
                'status'  => 'success',
                'total'   => $total,
                'proses'  => $proses,
                'selesai' => $selesai,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
