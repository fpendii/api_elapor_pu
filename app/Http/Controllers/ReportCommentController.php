<?php

namespace App\Http\Controllers;
use App\Models\Report;
use App\Models\ReportComment;
use Illuminate\Http\Request;

class ReportCommentController extends Controller
{
    public function store(Request $request, Report $report)
    {
        $data = $request->validate([
            'pesan' => 'required|string',
            'foto_progress' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto_progress')) {
            $data['foto_progress'] = $request->file('foto_progress')
                ->store('progress', 'public');
        }

        $data['user_id'] = auth()->id();
        $data['report_id'] = $report->id;

        ReportComment::create($data);

        return back()->with('success', 'Komentar berhasil dikirim');
    }
}
