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

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- STEPPER PROGRESS (Versi Perbaikan) --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="stepper-horizontal-container">
                <div class="stepper-wrapper">
                    @php
                        // KUNCI (KIRI) harus SAMA PERSIS dengan ENUM di database
                        $statuses = [
                            'Proposal' => 'Proposal',
                            'Verifikasi' => 'Cek Lokasi',
                            'Penetapan' => 'Penetapan',
                            'Pelaksanaan' => 'Pelaksanaan',
                            'Pemeriksaan' => 'Pemeriksaan',
                            'Selesai' => 'Selesai',
                        ];
                        $currentStatus = $report->status;
                        $isCompleted = true;
                    @endphp

                    @foreach ($statuses as $key => $label)
                        <div
                            class="stepper-item {{ $currentStatus == $key ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }}">
                            <div class="step-line"></div>
                            <div class="step-content">
                                <div class="step-counter">
                                    @if ($currentStatus == $key)
                                        {{-- Sedang Aktif: Tampilkan Nomor --}}
                                        {{ $loop->iteration }}
                                    @elseif ($isCompleted)
                                        {{-- Sudah Lewat (Completed): Tampilkan Checkmark atau Kosongkan untuk Hijau Polos --}}
                                        <i class="fas fa-check" style="font-size: 12px;"></i>
                                    @else
                                        {{-- Belum Sampai: Tampilkan Nomor --}}
                                        {{ $loop->iteration }}
                                    @endif
                                </div>
                                <div class="step-name">{{ $label }}</div>
                            </div>
                        </div>
                        @php
                            if ($currentStatus == $key) {
                                $isCompleted = false;
                            }
                        @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{-- KIRI: DETAIL LAPORAN --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-file-alt me-2 text-warning"></i>Informasi Aduan</h5>
                    @php
                        $badge = match ($report->status) {
                            'Proposal Susulan' => 'secondary',
                            'Proposal Ditolak' => 'danger',
                            'Cek Lokasi' => 'info',
                            'Penetapan Pekerjaan' => 'primary',
                            'Pelaksanaan' => 'warning',
                            'Pemeriksaan' => 'dark',
                            'Selesai' => 'success',
                            default => 'secondary',
                        };
                    @endphp
                    <span class="badge rounded-pill bg-{{ $badge }} px-3 py-2">
                        {{ strtoupper($report->status) }}
                    </span>
                </div>

                <div class="card-body">
                    {{-- FOTO SLIDER (MULTIPLE IMAGES) --}}
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold mb-2 d-block">Dokumentasi Kerusakan</label>
                        @if ($report->images->count() > 0)
                            <div id="carouselReport" class="carousel slide bg-dark rounded shadow-sm"
                                data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($report->images as $key => $image)
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $image->path) }}"
                                                    class="d-block w-100 rounded img-hover"
                                                    style="max-height: 450px; object-fit: contain;" alt="Foto Aduan">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                @if ($report->images->count() > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselReport"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselReport"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                @endif
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted italic">Klik gambar untuk memperbesar • Total
                                    {{ $report->images->count() }} foto</small>
                            </div>
                        @else
                            @if ($report->foto_kerusakan)
                                <div class="text-center bg-dark rounded p-2">
                                    <a href="{{ asset('storage/' . $report->foto_kerusakan) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $report->foto_kerusakan) }}"
                                            class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-light border text-center py-4">
                                    <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                    <p class="mb-0 text-muted">Tidak ada foto dokumentasi.</p>
                                </div>
                            @endif
                        @endif
                    </div>


                    {{-- INFORMASI UTAMA --}}
                    <div class="row mb-4 mt-4">
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small text-uppercase fw-bold">Judul Laporan</label>
                            <p class="fs-4 fw-bold text-dark mb-0">{{ $report->judul }}</p>
                        </div>

                        {{-- TAMPILAN JENIS USULAN (LONG TEXT FRIENDLY) --}}
                        <div class="col-md-12 mb-4">
                            <div class="p-3 border rounded bg-light shadow-sm">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">
                                    <i class="fas fa-paper-plane me-1 text-primary"></i> Jenis Usulan / Perihal
                                </label>
                                <p class="mb-0 text-dark fw-semibold" style="line-height: 1.6;">
                                    {{ $report->jenis_usulan ?? 'Tidak ada keterangan jenis usulan' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-bold"><i
                                    class="fas fa-tags me-1 text-primary"></i> Kategori Utama</label>
                            <div>
                                @php
                                    $katEksplode = explode(' - ', $report->kategori);
                                    $parentKat = $katEksplode[0];
                                    $subKat = $katEksplode[1] ?? null;
                                @endphp
                                <span class="badge bg-primary px-3 py-2 shadow-sm">{{ $parentKat }}</span>
                            </div>
                        </div>

                        @if ($subKat)
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label class="text-muted small text-uppercase fw-bold"><i
                                        class="fas fa-search me-1 text-info"></i> Detail Bagian</label>
                                <div>
                                    <span class="badge bg-info text-white px-3 py-2 shadow-sm">{{ $subKat }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4 p-3 bg-light rounded border-start border-4 border-warning shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <label class="text-muted small text-uppercase fw-bold">
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i> Lokasi Kejadian
                                </label>
                                <p class="mb-0 text-dark fw-bold">{{ $report->lokasi }}</p>
                            </div>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($report->lokasi) }}"
                                target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-external-link-alt"></i> Cek Map
                            </a>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold">Deskripsi Laporan</label>
                        <p class="text-dark lh-base">{{ $report->deskripsi }}</p>
                    </div>
                </div>

                {{-- FOOTER: AKSI STATUS SESUAI ALUR KERJA BARU --}}
                <div class="card-footer bg-white py-3 border-top d-flex gap-2">

                    {{-- STEP 1: PROPOSAL --}}
                    @if (in_array($report->status, ['Proposal Susulan', 'Proposal Ditolak', 'Menunggu']))
                        <button type="button" class="btn btn-success flex-grow-1 fw-bold text-white shadow-sm"
                            onclick="confirmStatus('Terima Proposal', 'form-step-1')">
                            <i class="fas fa-check-circle me-1"></i> Terima Proposal
                        </button>
                        <form id="form-step-1" method="POST"
                            action="{{ route('admin.laporan.update-status', [$report->id, 'Cek Lokasi']) }}"
                            class="d-none">@csrf</form>

                        <button type="button" class="btn btn-outline-danger flex-grow-1 fw-bold shadow-sm"
                            onclick="confirmStatus('Tolak Proposal', 'form-step-1-reject')">
                            <i class="fas fa-times-circle me-1"></i> Tolak Proposal
                        </button>
                        <form id="form-step-1-reject" method="POST"
                            action="{{ route('admin.laporan.update-status', [$report->id, 'Proposal Ditolak']) }}"
                            class="d-none">@csrf</form>
                    @endif

                    {{-- STEP 2: CEK LOKASI --}}
                    @if ($report->status === 'Cek Lokasi')
                        <button type="button" class="btn btn-info flex-grow-1 fw-bold text-white shadow-sm"
                            onclick="confirmStatus('Selesai Verifikasi Lokasi', 'form-step-2')">
                            <i class="fas fa-map-marked-alt me-1"></i> Verifikasi Lokasi & Lanjut Penetapan
                        </button>
                        <form id="form-step-2" method="POST"
                            action="{{ route('admin.laporan.update-status', [$report->id, 'Penetapan Pekerjaan']) }}"
                            class="d-none">@csrf</form>
                    @endif

                    {{-- STEP 3: PENETAPAN --}}
                    @if ($report->status === 'Penetapan Pekerjaan')
                        <button type="button" class="btn btn-primary flex-grow-1 fw-bold shadow-sm"
                            onclick="confirmStatus('Mulai Pelaksanaan', 'form-step-3')">
                            <i class="fas fa-tools me-1"></i> Tetapkan Pekerja & Mulai Pelaksanaan
                        </button>
                        <form id="form-step-3" method="POST"
                            action="{{ route('admin.laporan.update-status', [$report->id, 'Pelaksanaan']) }}"
                            class="d-none">@csrf</form>
                    @endif

                    {{-- STEP 4: PELAKSANAAN --}}
                    @if ($report->status === 'Pelaksanaan')
                        <button type="button" class="btn btn-warning flex-grow-1 fw-bold shadow-sm"
                            onclick="confirmStatus('Selesai Pekerjaan', 'form-step-4')">
                            <i class="fas fa-hard-hat me-1"></i> Ajukan Pemeriksaan Pekerjaan
                        </button>
                        <form id="form-step-4" method="POST"
                            action="{{ route('admin.laporan.update-status', [$report->id, 'Pemeriksaan']) }}"
                            class="d-none">@csrf</form>
                    @endif

                    {{-- STEP 5: PEMERIKSAAN --}}
                    @if ($report->status === 'Pemeriksaan')
                        <button type="button" class="btn btn-success flex-grow-1 fw-bold shadow-sm"
                            onclick="confirmStatus('Selesaikan Laporan', 'form-step-5')">
                            <i class="fas fa-flag-checkered me-1"></i> Semua Oke, Tandai Selesai
                        </button>
                        <form id="form-step-5" method="POST"
                            action="{{ route('admin.laporan.update-status', [$report->id, 'Selesai']) }}" class="d-none">
                            @csrf</form>
                    @endif
                </div>
            </div>

            {{-- HASIL ANALISIS AI (Tetap Ada) --}}
            @if ($report->ai_analysis || $report->ai_damage_type || $report->ai_severity)
                <div class="card border-0 shadow-sm mb-4 border-top border-4 border-info">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-info"><i class="fas fa-robot me-2"></i>Hasil Analisis AI Gemini</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">Jenis Kerusakan</label>
                                <p class="fw-bold text-dark mb-0">{{ $report->ai_damage_type ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">Tingkat Keparahan</label>
                                @php
                                    $severityColor = match (strtolower($report->ai_severity)) {
                                        'ringan' => 'success',
                                        'sedang' => 'warning',
                                        'berat', 'parah' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $severityColor }} px-3 py-2">
                                    {{ strtoupper($report->ai_severity ?? 'Unknown') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-3 bg-light rounded border-start border-4 border-info">
                            <label class="text-muted small text-uppercase fw-bold">Kesimpulan Analisis</label>
                            <p class="mb-0 text-dark lh-base">{{ $report->ai_analysis }}</p>
                        </div>
                    </div>
                </div>
            @endif

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
                                    <small
                                        class="badge bg-light text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="p-3 bg-light rounded shadow-sm text-dark border">
                                    {{ $comment->pesan }}
                                    @if ($comment->foto_progress)
                                        <div class="mt-3">
                                            <img src="{{ asset('storage/' . $comment->foto_progress) }}"
                                                class="img-fluid rounded border shadow-sm" style="max-width: 250px">
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

            {{-- FORM UPDATE PROGRESS --}}
            @if ($report->status !== 'Selesai')
                <div class="card border-0 shadow-sm mb-4 border-top border-primary border-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 small text-uppercase">Tambah Update Progress</h5>
                        <form method="POST" action="{{ route('admin.laporan.komentar.store', $report->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="report_id" value="{{ $report->id }}">
                            <div class="mb-3">
                                <textarea name="pesan" class="form-control bg-light" rows="3"
                                    placeholder="Berikan update progress atau pesan ke pelapor..." required></textarea>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <label class="small text-muted d-block mb-1">Lampirkan Foto Progress (Opsional)</label>
                                    <input type="file" name="foto_progress" class="form-control form-control-sm">
                                </div>
                                <button class="btn btn-primary px-4 fw-bold shadow-sm align-self-end">Kirim Update</button>
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
                        @if ($report->user->verifikasi == 'acc')
                            <span
                                class="badge bg-success-subtle text-success border border-success px-3">TERVERIFIKASI</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger px-3">BELUM
                                VERIFIKASI</span>
                        @endif
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="text-start">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-light text-success me-3"><i class="fab fa-whatsapp"></i></div>
                            <div>
                                <div class="small text-muted">WhatsApp</div>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $report->user->nomor_wa) }}"
                                    target="_blank" class="fw-bold text-success text-decoration-none">
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
        .stepper-horizontal-container {
            width: 100%;
            overflow-x: auto;
            padding: 10px 0;
        }

        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            min-width: 600px;
            /* Menjaga agar tidak terlalu rapat di layar kecil */
        }

        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        /* Garis Penghubung */
        .stepper-item .step-line {
            position: absolute;
            top: 20px;
            /* Setengah dari tinggi step-counter */
            left: 50%;
            right: -50%;
            height: 2px;
            background-color: #e0e0e0;
            z-index: 1;
        }

        .stepper-item:last-child .step-line {
            display: none;
        }

        /* Warna Garis Jika Selesai */
        .stepper-item.completed .step-line {
            background-color: #198754;
        }

        .stepper-item.active .step-line {
            background-color: #e0e0e0;
            /* Garis setelah posisi aktif tetap abu-abu */
        }

        /* Konten Step */
        .stepper-item .step-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .stepper-item .step-counter {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            color: #9e9e9e;
            transition: all 0.3s ease;
        }

        .stepper-item .step-name {
            margin-top: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #9e9e9e;
            text-align: center;
            white-space: nowrap;
        }

        /* Status Aktif */
        .stepper-item.active .step-counter {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
        }

        .stepper-item.active .step-name {
            color: #0d6efd;
        }

        /* Status Selesai (Completed) */
        .stepper-item.completed .step-counter {
            background-color: #198754;
            color: #fff;
            border-color: #198754;
        }

        .stepper-item.completed .step-name {
            color: #198754;
        }
    </style>

    <script>
        function confirmStatus(nextStep, formId) {
            Swal.fire({
                title: 'Konfirmasi Perubahan Status',
                text: "Apakah anda yakin ingin melanjutkan ke tahap: " + nextStep + "?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

@endsection
