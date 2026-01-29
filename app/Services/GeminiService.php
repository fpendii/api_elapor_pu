<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    // Gunakan endpoint STABLE v1
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function analyzeDamage($imagePath)
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);
            if (!file_exists($fullPath)) return null;

            $imageData = base64_encode(file_get_contents($fullPath));

            $response = Http::post($this->apiUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'role' => 'user', // Menambahkan role 'user' seringkali wajib di versi terbaru
                        'parts' => [
                            ['text' => "Analisis gambar ini. Berikan jawaban dalam format JSON mentah: { \"analysis\": \"...\", \"type\": \"...\", \"severity\": \"...\" }"],
                            [
                                'inline_data' => [
                                    'mime_type' => 'image/jpeg',
                                    'data' => $imageData
                                ]
                            ]
                        ]
                    ]
                ],
                // Menambahkan config agar AI tidak memberikan teks Markdown yang merusak JSON
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                ]
            ]);

            if ($response->failed()) {
                Log::error("Gemini Error: " . $response->body());
                return null;
            }

            $result = $response->json();
            return json_decode($result['candidates'][0]['content']['parts'][0]['text'], true);
        } catch (\Exception $e) {
            Log::error('Critical AI Error: ' . $e->getMessage());
            return null;
        }
    }
}
