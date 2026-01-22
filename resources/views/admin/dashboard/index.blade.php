@extends('layouts.admin')

@section('title', 'Dashboard Masyarakat')
@section('page-title', 'Ringkasan Laporan')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <h4 class="fw-bold text-dark mb-1">Halo, {{ auth()->user()->name }}! 👋</h4>
                <p class="text-muted mb-0">Berikut adalah status laporan pengaduan Anda saat ini.</p>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 text-center h-100">
                <small class="text-muted fw-medium d-block mb-1">Total Laporan</small>
                <h2 class="fw-bold mb-0 text-primary">{{ $stats['total'] }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 text-center h-100">
                <small class="text-muted fw-medium d-block mb-1">Pending</small>
                <h2 class="fw-bold mb-0 text-warning">{{ $stats['pending'] }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 text-center h-100">
                <small class="text-muted fw-medium d-block mb-1">Diproses</small>
                <h2 class="fw-bold mb-0 text-info">{{ $stats['proses'] }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 text-center h-100">
                <small class="text-muted fw-medium d-block mb-1">Selesai</small>
                <h2 class="fw-bold mb-0 text-success">{{ $stats['selesai'] }}</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Laporan Terbaru</h5>
                    {{-- <a href="#" class="btn btn-sm btn-warning fw-semibold px-3">+ Buat Laporan</a> --}}
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Judul Laporan</th>
                                    <th>Kategori</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    {{-- <th class="text-center">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_reports as $report)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-semibold text-dark">{{ $report->judul }}</span>
                                        <br><small class="text-muted">{{ Str::limit($report->lokasi, 30) }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary opacity-75">{{ $report->kategori }}</span></td>
                                    <td>{{ $report->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($report->status == 'pending')
                                            <span class="badge rounded-pill bg-warning text-dark px-3">Menunggu</span>
                                        @elseif($report->status == 'proses')
                                            <span class="badge rounded-pill bg-info px-3">Proses</span>
                                        @else
                                            <span class="badge rounded-pill bg-success px-3">Selesai</span>
                                        @endif
                                    </td>
                                    {{-- <td class="text-center">
                                        <a href="{{ route('laporan.show', $report->id) }}" class="btn btn-light btn-sm rounded-pill px-3 fw-medium">Detail</a>
                                    </td> --}}
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada laporan yang dikirimkan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection