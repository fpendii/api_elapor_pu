<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{

    public function index(Request $request)
    {
        // Mengambil laporan berdasarkan user_id yang dikirim lewat query parameter
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

        // 3. Simpan ke Database
        $report = Report::create([
            'user_id'        => $request->user_id,
            'kategori'       => $request->kategori,
            'judul'          => $request->judul,
            'lokasi'         => $request->lokasi,
            'deskripsi'      => $request->deskripsi,
            'foto_kerusakan' => $fotoPath,
            'status'         => 'Menunggu',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Laporan berhasil dikirim!',
            'data' => $report
        ], 201);
    }
}
