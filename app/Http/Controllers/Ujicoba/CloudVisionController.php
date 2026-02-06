<?php

namespace App\Http\Controllers\Ujicoba;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
// use Illuminate\Http\Request;

class CloudVisionController extends Controller
{
    public function test()
    {
        $response = Http::post(
            'https://vision.googleapis.com/v1/images:annotate?key=' . env('GOOGLE_VISION_KEY'),
            [
                'requests' => [[
                    'image' => [
                        'source' => [
                            'imageUri' => 'https://upload.wikimedia.org/wikipedia/commons/3/3f/Jalan_berlubang.jpg'
                        ]
                    ],
                    'features' => [
                        ['type' => 'LABEL_DETECTION'],
                        ['type' => 'TEXT_DETECTION'],
                    ],
                ]]
            ]
        );
// dd(env('GOOGLE_VISION_KEY'));
        return response()->json([
            'status' => $response->status(),
            'data'   => $response->json(),
        ]);
    }
}
