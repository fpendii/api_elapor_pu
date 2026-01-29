@extends('layouts.admin')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan Aduan')

@section('content')

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- TOMBOL KEMBALI --}}
<div class="mb-3">
    <a href="{{ route('admin.laporan.index') }}" class="btn btn-light border shadow-sm px-3">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Laporan
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    {{-- KIRI: DETAIL LAPORAN --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-file-alt me-2 text-warning"></i>Informasi Aduan</h5>
                @php
                    $badge = match($report->status) {
                        'Menunggu' => 'warning',
                        'Proses'   => 'info',
                        'Selesai'  => 'success',
                        'Ditolak'  => 'danger',
                        default    => 'secondary'
                    };
                @endphp
                <span class="badge rounded-pill bg-{{ $badge }} px-3 py-2">
                    {{ strtoupper($report->status) }}
                </span>
            </div>

            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-7">
                        <label class="text-muted small text-uppercase fw-bold">Judul Laporan</label>
                        <p class="fs-5 fw-bold text-dark">{{ $report->judul }}</p>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <label class="text-muted small text-uppercase fw-bold">Kategori</label>
                        <p><span class="badge bg-light text-primary border border-primary">{{ $report->kategori }}</span></p>
                    </div>
                </div>

                <div class="mb-4 p-3 bg-light rounded border-start border-4 border-warning">
                    <label class="text-muted small text-uppercase fw-bold"><i class="fas fa-map-marker-alt me-1"></i> Lokasi</label>
                    <p class="mb-0 text-dark fw-medium">{{ $report->lokasi }}</p>
                </div>

                <div class="mb-4">
                    <label class="text-muted small text-uppercase fw-bold">Deskripsi</label>
                    <p class="text-dark lh-base">{{ $report->deskripsi }}</p>
                </div>

                @if($report->foto_kerusakan)
                <div class="mb-2 text-center bg-dark rounded p-2">
                    <a href="{{ asset('storage/'.$report->foto_kerusakan) }}" target="_blank">
                        <img src="{{ asset('storage/'.$report->foto_kerusakan) }}"
                             class="img-fluid rounded shadow-sm img-hover"
                             style="max-height: 450px; object-fit: contain;">
                    </a>
                </div>
                @endif
            </div>

            {{-- FOOTER: AKSI STATUS --}}
            <div class="card-footer bg-white py-3 border-top d-flex gap-2">
                @if($report->status === 'Menunggu')
                    <button type="button" class="btn btn-info flex-grow-1 fw-bold text-white shadow-sm"
                            onclick="confirmStatus('Proses', 'form-terima')">
                        <i class="fas fa-spinner fa-spin-hover me-1"></i> Mulai Proses
                    </button>
                    <form id="form-terima" method="POST" action="{{ route('admin.laporan.terima', $report->id) }}" class="d-none">@csrf</form>

                    <button type="button" class="btn btn-outline-danger flex-grow-1 fw-bold shadow-sm"
                            onclick="confirmStatus('Tolak', 'form-tolak')">
                        <i class="fas fa-times me-1"></i> Tolak
                    </button>
                    <form id="form-tolak" method="POST" action="{{ route('admin.laporan.tolak', $report->id) }}" class="d-none">@csrf</form>
                @endif

                @if($report->status === 'Proses')
                    <button type="button" class="btn btn-success w-100 fw-bold shadow-sm"
                            onclick="confirmStatus('Selesai', 'form-selesai')">
                        <i class="fas fa-check-double me-1"></i> Tandai Sudah Selesai
                    </button>
                    <form id="form-selesai" method="POST" action="{{ route('admin.laporan.selesai', $report->id) }}" class="d-none">@csrf</form>
                @endif
            </div>
        </div>

        {{-- RIWAYAT PROSES (TIMELINE) --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-secondary"><i class="fas fa-stream me-2"></i>Riwayat Progress</h5>
            </div>
            <div class="card-body">
                <div class="timeline-container ps-3">
                    @forelse($report->comments as $comment)
                        <div class="timeline-item border-start border-2 ps-4 pb-4 position-relative">
                            <div class="timeline-dot bg-primary shadow"></div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark">{{ $comment->user->name }}</span>
                                <small class="badge bg-light text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="p-3 bg-light rounded shadow-sm text-dark border">
                                {{ $comment->pesan }}
                                @if($comment->foto_progress)
                                    <div class="mt-3">
                                        <img src="{{ asset('storage/'.$comment->foto_progress) }}"
                                             class="img-fluid rounded border shadow-sm"
                                             style="max-width: 200px">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">Belum ada riwayat aktivitas.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- FORM UPDATE --}}
        @if(!in_array($report->status, ['Ditolak', 'Selesai']))
        <div class="card border-0 shadow-sm mb-4 border-top border-primary border-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3 small text-uppercase">Tambah Update Progress</h5>
                <form method="POST" action="{{ route('admin.laporan.komentar.store', $report->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <textarea name="pesan" class="form-control bg-light" rows="3" placeholder="Pesan untuk pelapor..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <input type="file" name="foto_progress" class="form-control form-control-sm w-50">
                        <button class="btn btn-primary px-4 fw-bold shadow-sm">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    {{-- KANAN: DATA PELAPOR --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
            <div class="card-header bg-dark text-white text-center py-3">
                <h6 class="mb-0 fw-bold small text-uppercase">Identitas Pelapor</h6>
            </div>
            <div class="card-body text-center">
                <div class="user-avatar bg-warning text-dark mx-auto mb-3 shadow">
                    {{ strtoupper(substr($report->user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold text-dark mb-0">{{ $report->user->name }}</h5>
                <small class="text-muted">{{ $report->user->email }}</small>

                <div class="mt-3">
                    @if($report->user->verifikasi == 'acc')
                        <span class="badge bg-success-subtle text-success border border-success px-3">TERVERIFIKASI</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger px-3">BELUM VERIFIKASI</span>
                    @endif
                </div>

                <hr class="my-4 opacity-50">

                <div class="text-start">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-light text-success me-3"><i class="fab fa-whatsapp"></i></div>
                        <div>
                            <div class="small text-muted">WhatsApp</div>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $report->user->nomor_wa) }}" target="_blank" class="fw-bold text-success text-decoration-none">
                                {{ $report->user->nomor_wa }}
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-light text-primary me-3"><i class="fas fa-calendar-alt"></i></div>
                        <div>
                            <div class="small text-muted">Waktu Lapor</div>
                            <div class="fw-bold text-dark small">{{ $report->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .img-hover { transition: all 0.3s ease; }
    .img-hover:hover { transform: scale(1.01); filter: brightness(1.1); }
    .user-avatar { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold; }
    .icon-box { width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 16px; }
    .timeline-item { position: relative; border-left-color: #dee2e6 !important; }
    .timeline-dot { position: absolute; left: -9px; top: 5px; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; }
</style>

<script>
function confirmStatus(action, formId) {
    let title, text, icon, confirmColor;

    if (action === 'Proses') {
        title = 'Mulai Proses?';
        text = 'Status akan berubah menjadi PROSES.';
        icon = 'info';
        confirmColor = '#0dcaf0';
    } else if (action === 'Tolak') {
        title = 'Tolak Laporan?';
        text = 'Tindakan ini tidak dapat dibatalkan.';
        icon = 'warning';
        confirmColor = '#dc3545';
    } else {
        title = 'Selesaikan?';
        text = 'Pastikan semua perbaikan sudah selesai.';
        icon = 'success';
        confirmColor = '#198754';
    }

    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Memproses...', didOpen: () => { Swal.showLoading(); } });
            document.getElementById(formId).submit();
        }
    });
}
</script>

@endsection
