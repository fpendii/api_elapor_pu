@extends('layouts.admin')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan Aduan')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row">

    {{-- KIRI: DETAIL LAPORAN --}}
    <div class="col-lg-8">

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning fw-semibold">
                Detail Laporan
            </div>

            <div class="card-body">
                <p><strong>Judul</strong><br>{{ $report->judul }}</p>
                <p><strong>Kategori</strong><br>{{ $report->kategori }}</p>
                <p><strong>Lokasi</strong><br>{{ $report->lokasi }}</p>

                <p>
                    <strong>Status</strong><br>
                    @php
                        $badge = match($report->status) {
                            'Menunggu' => 'warning',
                            'Proses'   => 'info',
                            'Selesai'  => 'success',
                            'Ditolak'  => 'danger',
                            default    => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $badge }}">
                        {{ strtoupper($report->status) }}
                    </span>
                </p>

                <p><strong>Deskripsi</strong><br>{{ $report->deskripsi }}</p>

                @if($report->foto_kerusakan)
                    <img src="{{ asset('storage/'.$report->foto_kerusakan) }}"
                         class="img-fluid rounded border"
                         style="max-width: 400px">
                @endif
            </div>

            {{-- AKSI STATUS --}}
            <div class="card-footer d-flex gap-2">
                @if($report->status === 'Menunggu')
                    <form method="POST" action="{{ route('admin.laporan.terima', $report->id) }}">
                        @csrf
                        <button class="btn btn-info">Proses</button>
                    </form>

                    <form method="POST" action="{{ route('admin.laporan.tolak', $report->id) }}">
                        @csrf
                        <button class="btn btn-danger">Tolak</button>
                    </form>
                @endif

                @if($report->status === 'Proses')
                    <form method="POST" action="{{ route('admin.laporan.selesai', $report->id) }}">
                        @csrf
                        <button class="btn btn-success">Tandai Selesai</button>
                    </form>
                @endif
            </div>
        </div>

        {{-- KOMENTAR --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                Riwayat Komentar
            </div>

            <div class="card-body">
                @forelse($report->comments as $comment)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $comment->user->name }}</strong>
                            <small>{{ $comment->created_at->format('d M Y H:i') }}</small>
                        </div>

                        <p class="mt-2 mb-2">{{ $comment->pesan }}</p>

                        @if($comment->foto_progress)
                            <img src="{{ asset('storage/'.$comment->foto_progress) }}"
                                 class="img-fluid rounded border"
                                 style="max-width: 300px">
                        @endif
                    </div>
                @empty
                    <p class="text-muted">Belum ada komentar</p>
                @endforelse
            </div>
        </div>

        {{-- FORM KOMENTAR --}}
        @if(!in_array($report->status, ['Ditolak', 'Selesai']))
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                Tambah Komentar
            </div>

            <div class="card-body">
                <form method="POST"
                      action="{{ route('admin.laporan.komentar.store', $report->id) }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Pesan</label>
                        <textarea name="pesan" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Progress (opsional)</label>
                        <input type="file" name="foto_progress" class="form-control">
                    </div>

                    <button class="btn btn-primary">Kirim Komentar</button>
                </form>
            </div>
        </div>
        @endif

    </div>

    {{-- KANAN: DATA PELAPOR --}}
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                Data Pelapor
            </div>

            <div class="card-body">
                <p><strong>Nama</strong><br>{{ $report->user->name }}</p>
                <p><strong>Email</strong><br>{{ $report->user->email }}</p>
                <p><strong>No WhatsApp</strong><br>{{ $report->user->nomor_wa }}</p>

                <p>
                    <strong>Status Verifikasi</strong><br>
                    @if($report->user->verifikasi)
                        <span class="badge bg-success">TERVERIFIKASI</span>
                    @else
                        <span class="badge bg-danger">BELUM VERIFIKASI</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
