<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\GeminiService; // 1. Import Service AI
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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
        // ===============================
        // 1. VALIDASI
        // ===============================
        $validator = Validator::make($request->all(), [
            'user_id'        => 'required',
            'kategori'       => 'required',
            'judul'          => 'required',
            'lokasi'         => 'required',
            'deskripsi'      => 'required',
            'foto_kerusakan' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // ===============================
        // 2. SIMPAN FOTO
        // ===============================
        $file = $request->file('foto_kerusakan');
        $namaFile = time() . '_report.' . $file->getClientOriginalExtension();
        $fotoPath = $file->storeAs('reports', $namaFile, 'public');

        // ===============================
        // 3. SIMPAN REPORT AWAL
        // ===============================
        $report = Report::create([
            'user_id'        => $request->user_id,
            'kategori'       => $request->kategori,
            'judul'          => $request->judul,
            'lokasi'         => $request->lokasi,
            'deskripsi'      => $request->deskripsi,
            'foto_kerusakan' => $fotoPath,
            'status'         => 'Menunggu',
        ]);

        // ===============================
        // 4. ANALISIS AI (GEMINI)
        // ===============================
        try {
            $apiKey = env('GOOGLE_VISION_KEY');
            $imagePath = storage_path('app/public/' . $fotoPath);

            $prompt = 'Balas HANYA dalam JSON VALID tanpa teks lain.
Format: {"analysis":"", "damage_type":"", "severity":"ringan|sedang|berat"}';

            $payload = [
                'contents' => [[
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $file->getMimeType(),
                                'data' => base64_encode(file_get_contents($imagePath)),
                            ]
                        ]
                    ]
                ]]
            ];

            $response = Http::post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                $payload
            );

            $rawText = $response['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($rawText) {

                // bersihkan ```json ``` wrapper
                $cleanText = trim(preg_replace('/```json|```/i', '', $rawText));

                $aiResult = json_decode($cleanText, true);

                if (json_last_error() === JSON_ERROR_NONE) {

                    // NORMALISASI DAMAGE TYPE
                    $damageType = null;
                    if (!empty($aiResult['damage_type'])) {
                        $damageType = trim(
                            explode(',', strtolower($aiResult['damage_type']))[0]
                        );
                    }

                    // NORMALISASI SEVERITY
                    $severity = strtolower($aiResult['severity'] ?? null);
                    if (!in_array($severity, ['ringan', 'sedang', 'berat'])) {
                        $severity = null;
                    }

                    $report->update([
                        'ai_analysis'    => $aiResult['analysis'] ?? null,
                        'ai_damage_type' => $damageType,
                        'ai_severity'    => $severity,
                    ]);
                } else {
                    \Log::error('AI JSON ERROR: ' . json_last_error_msg());
                    \Log::error('AI RAW: ' . $rawText);
                }
            }
        } catch (\Throwable $e) {
            \Log::error('AI ANALYSIS ERROR: ' . $e->getMessage());
            // laporan tetap tersimpan walau AI gagal
        }

        // ===============================
        // 5. RESPONSE
        // ===============================
        return response()->json([
            'status'  => true,
            'message' => 'Laporan berhasil dikirim dan dianalisis AI',
            'data'    => $report->fresh()
        ], 201);
    }



    // public function store(Request $request)
    // {
    //     // 1. Validasi Input
    //     $validator = Validator::make($request->all(), [
    //         'user_id'         => 'required',
    //         'kategori'        => 'required',
    //         'judul'           => 'required',
    //         'lokasi'          => 'required',
    //         'deskripsi'       => 'required',
    //         'foto_kerusakan'  => 'required|image|mimes:jpeg,png,jpg|max:5120',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validasi Gagal',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     // 2. Simpan Foto
    //     $fotoPath = null;
    //     if ($request->hasFile('foto_kerusakan')) {
    //         $file = $request->file('foto_kerusakan');
    //         $namaFile = time() . '_report.' . $file->getClientOriginalExtension();
    //         $fotoPath = $file->storeAs('reports', $namaFile, 'public');
    //     }

    //     // 3. Simpan ke Database (Awal)
    //     $report = Report::create([
    //         'user_id'        => $request->user_id,
    //         'kategori'       => $request->kategori,
    //         'judul'          => $request->judul,
    //         'lokasi'         => $request->lokasi,
    //         'deskripsi'      => $request->deskripsi,
    //         'foto_kerusakan' => $fotoPath,
    //         'status'         => 'Menunggu',
    //     ]);

    //     // 4. ANALISIS AI (Otomatis setelah save)
    //     try {
    //         if ($fotoPath) {
    //             $aiResult = $this->aiService->analyzeDamage($fotoPath);

    //             if ($aiResult) {
    //                 $report->update([
    //                     'ai_analysis'    => $aiResult['analysis'] ?? null,
    //                     'ai_damage_type' => $aiResult['type'] ?? null,
    //                     'ai_severity'    => $aiResult['severity'] ?? null,
    //                 ]);
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         // Kita bungkus try-catch agar jika AI gagal/limit, laporan tetap tersimpan
    //         Log::error("Gagal Analisis AI: " . $e->getMessage());
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Laporan berhasil dikirim dan dianalisis oleh AI!',
    //         'data' => $report->fresh() // Mengambil data terbaru setelah update AI
    //     ], 201);
    // }
}
