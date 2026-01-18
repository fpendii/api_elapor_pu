<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReportComment;
use Illuminate\Http\Request;

class ReportCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'report_id' => 'required|exists:reports,id',
            'user_id'   => 'required|exists:users,id',
            'pesan'     => 'required',
        ]);

        $comment = ReportComment::create([
            'report_id' => $request->report_id,
            'user_id'   => $request->user_id,
            'pesan'     => $request->pesan,
        ]);

        // Load relasi user agar nama pembuat komen muncul di Flutter
        return response()->json([
            'status' => true,
            'data'   => $comment->load('user')
        ], 201);
    }
}
