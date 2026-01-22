<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class DashboardControllerAdmin extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Statistik untuk widget
        $stats = [
            'total'     => Report::where('user_id', $userId)->count(),
            'pending'   => Report::where('user_id', $userId)->where('status', 'pending')->count(),
            'proses'    => Report::where('user_id', $userId)->where('status', 'proses')->count(),
            'selesai'   => Report::where('user_id', $userId)->where('status', 'selesai')->count(),
        ];

        // Ambil 5 laporan terbaru milik user
        $recent_reports = Report::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recent_reports'));
    }
}
