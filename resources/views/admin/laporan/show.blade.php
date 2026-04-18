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

                {{-- INFO KATEGORI & PRIORITAS --}}
                <div class="row g-3 mb-4">

                    {{-- KATEGORI UTAMA (JENIS DPA) --}}
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="text-muted small text-uppercase fw-bold mb-0">
                                    <is class="fas fa-tags me-1 text-primary"></is> Jenis Kegiatan
                                </label>

                                {{-- Tombol ubah muncul jika status Verifikasi atau Penetapan --}}
                                @if (in_array($report->status, ['Verifikasi', 'Penetapan']))
                                <button type="button" class="btn btn-sm btn-outline-primary border-0 fw-bold"
                                    data-bs-toggle="modal" data-bs-target="#modalGantiDPA">
                                    <i class="fas fa-edit me-1"></i> Ubah
                                </button>
                                @endif
                            </div>

                            <div class="d-flex align-items-center mt-2">
                                <span class="badge bg-primary fs-6 px-3 py-2 shadow-sm">
                                    <i class="fas fa-file-invoice-dollar me-1"></i>
                                    {{ $report->jenisRab->nama_rab ?? 'Belum Ditentukan' }}
                                </span>
                            </div>

                            @if (isset($report->jenisRab->dana))
                            <div class="small text-muted mt-2">
                                <i class="fas fa-coins me-1"></i> Total Dana Tersedia:
                                {{ $report->jenisRab->dana }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal fade" id="modalGantiDPA" tabindex="-1" aria-labelledby="modalGantiDPALabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="modalGantiDPALabel">
                                        <i class="fas fa-tasks me-2"></i> Pilih Jenis Kegiatan
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <div class="list-group list-group-flush"
                                        style="max-height: 400px; overflow-y: auto;">
                                        @forelse ($allJenisRab as $rab)
                                        <form
                                            action="{{ route('admin.laporan.update-jenis-dpa', [$report->id, $rab->id]) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 {{ $report->jenis_rab_id == $rab->id ? 'bg-light' : '' }}"
                                                onclick="return confirm('Apakah Anda yakin ingin mengubah Jenis DPA ke {{ $rab->nama_rab }}?')">
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $rab->nama_rab }}</div>
                                                    <small class="text-muted text-uppercase">
                                                        <i class="fas fa-money-bill-wave me-1"></i>
                                                        {{ $rab->dana }}
                                                    </small>
                                                </div>

                                                @if ($report->jenis_rab_id == $rab->id)
                                                <span class="badge bg-success rounded-pill">
                                                    <i class="fas fa-check"></i> Aktif
                                                </span>
                                                @else
                                                <i class="fas fa-chevron-right text-light"></i>
                                                @endif
                                            </button>
                                        </form>
                                        @empty
                                        <div class="p-4 text-center text-muted">
                                            <i class="fas fa-info-circle mb-2 fa-2x"></i>
                                            <p class="mb-0">Data Jenis RAB tidak tersedia.</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="modal-footer bg-light p-2">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PRIORITAS --}}
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light h-100 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="text-muted small text-uppercase fw-bold mb-0">
                                    <i class="fas fa-exclamation-circle me-1 text-danger"></i> Prioritas
                                </label>

                                @if ($report->status === 'Verifikasi' || $report->status === 'Penetapan')
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle border-0"
                                        data-bs-toggle="dropdown">
                                        Ubah
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        @foreach (['Darurat', 'Tinggi', 'Sedang', 'Rendah'] as $prio)
                                        <li>
                                            <button class="dropdown-item"
                                                onclick="confirmStatus('Set Prioritas ke {{ $prio }}', 'form-prio-{{ strtolower($prio) }}')">
                                                {{ $prio }}
                                            </button>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>

                            @php
                            $priorityColor = match (strtolower($report->prioritas)) {
                            'darurat' => 'bg-dark text-white',
                            'tinggi' => 'bg-danger',
                            'sedang' => 'bg-warning text-dark',
                            'rendah' => 'bg-info text-white',
                            default => 'bg-secondary',
                            };
                            @endphp

                            <span class="badge {{ $priorityColor }} fs-6 px-3 py-2 shadow-sm">
                                {{ $report->prioritas ?? 'Tidak Ada' }}
                            </span>

                            {{-- FORM PRIORITAS HIDDEN --}}
                            @foreach (['Darurat', 'Tinggi', 'Sedang', 'Rendah'] as $prio)
                            <form id="form-prio-{{ strtolower($prio) }}" method="POST"
                                action="{{ route('admin.laporan.update-status', [$report->id, $prio]) }}"
                                class="d-none">
                                @csrf
                                <input type="hidden" name="type" value="prioritas">
                            </form>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Tampilkan hanya jika status BUKAN Proposal atau Verifikasi --}}
                @if (!in_array($report->status, ['Proposal', 'Verifikasi']))
                <div class="card border-0 shadow-sm mb-4 border-start border-primary border-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-box bg-primary text-white me-3 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 45px; height: 45px;">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Data Anggaran & Dokumen</h6>
                                <small class="text-muted">Kategori: <strong>{{ $report->jenisRab->nama_rab ?? '-'
                                        }}</strong></small>
                            </div>
                        </div>

                        {{-- Form hanya aktif jika status == Penetapan --}}
                        <form action="{{ route('admin.jenis-rab.update-rab', $report->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                {{-- Input Nominal --}}
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Nominal Dana Kegiatan
                                        (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light fw-bold">Rp</span>
                                        <input type="text" id="nominal_rab_display"
                                            class="form-control form-control-lg @if($report->status !== 'Penetapan') bg-light @endif"
                                            placeholder="0"
                                            value="{{ number_format($report->nominal_rab, 0, ',', '.') }}" {{-- Kunci
                                            input jika bukan Penetapan --}}>

                                        <input type="hidden" name="nominal_rab" id="nominal_rab_real"
                                            value="{{ $report->nominal_rab }}">
                                    </div>
                                </div>

                                {{-- Upload Dokumen --}}
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Dokumen
                                        Lampiran</label>
                                    {{-- Sembunyikan input file jika status bukan Penetapan --}}
                                    @if ($report->status === 'Penetapan')
                                    <input type="file" name="dokumen_rab" class="form-control form-control-lg"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx">
                                    @else
                                    <input type="text" class="form-control form-control-lg bg-light"
                                        value="{{ $report->dokumen_rab ? 'Dokumen Terlampir' : 'Tidak Ada Dokumen' }}"
                                        readonly>
                                    @endif
                                </div>

                                {{-- Preview Dokumen --}}
                                @if ($report->dokumen_rab)
                                <div class="col-12">
                                    <div
                                        class="p-3 bg-light rounded d-flex align-items-center justify-content-between border">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                            <div>
                                                <div class="fw-bold mb-0">Dokumen Lampiran</div>
                                                <small class="text-muted">File sudah diunggah.</small>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $report->dokumen_rab) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary px-3">
                                            <i class="fas fa-eye me-1"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                </div>
                                @endif

                                {{-- Tombol Simpan Hanya Muncul di Status Penetapan --}}
                                {{-- @if ($report->status === 'Penetapan') --}}
                                <div class="col-12 text-end mt-4">
                                    <hr>
                                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                        <i class="fas fa-check-circle me-1"></i> Simpan & Perbarui Anggaran
                                    </button>
                                </div>
                                {{-- @endif --}}
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                {{-- FOOTER: TOMBOL PERUBAHAN STATUS --}}
                <div class="card-footer bg-white py-3 border-top d-flex gap-2">

                    {{-- 1. Tahap Proposal --}}
                    @if (in_array($report->status, ['Proposal', 'Proposal Ditolak', 'Menunggu']))
                    <button type="button" class="btn btn-success flex-grow-1 fw-bold"
                        onclick="confirmStatus('Terima & Cek Lokasi', 'form-to-verifikasi')">
                        <i class="fas fa-check-circle me-1"></i> Terima Proposal
                    </button>
                    <form id="form-to-verifikasi" method="POST"
                        action="{{ route('admin.laporan.update-status', [$report->id, 'Verifikasi']) }}" class="d-none">
                        @csrf</form>

                    <button type="button" class="btn btn-outline-danger flex-grow-1 fw-bold"
                        onclick="confirmStatus('Tolak Proposal', 'form-reject')">
                        <i class="fas fa-times-circle me-1"></i> Tolak
                    </button>
                    <form id="form-reject" method="POST"
                        action="{{ route('admin.laporan.update-status', [$report->id, 'Proposal Ditolak']) }}"
                        class="d-none">@csrf</form>
                    @endif

                    {{-- 2. Tahap Verifikasi --}}
                    @if ($report->status === 'Verifikasi')
                    <button type="button" class="btn btn-info text-white flex-grow-1 fw-bold"
                        onclick="confirmStatus('Lanjut ke Penetapan', 'form-to-penetapan')">
                        <i class="fas fa-map-marked-alt me-1"></i> Selesai Cek Lokasi
                    </button>
                    <form id="form-to-penetapan" method="POST"
                        action="{{ route('admin.laporan.update-status', [$report->id, 'Penetapan']) }}" class="d-none">
                        @csrf</form>
                    @endif

                    {{-- 3. Tahap Penetapan --}}
                    @if ($report->status === 'Penetapan')
                    <button type="button" class="btn btn-primary flex-grow-1 fw-bold"
                        onclick="confirmStatus('Mulai Pelaksanaan', 'form-to-pelaksanaan')">
                        <i class="fas fa-tools me-1"></i> Mulai Pelaksanaan
                    </button>
                    <form id="form-to-pelaksanaan" method="POST"
                        action="{{ route('admin.laporan.update-status', [$report->id, 'Pelaksanaan']) }}"
                        class="d-none">@csrf</form>
                    @endif

                    {{-- 4. Tahap Pelaksanaan --}}
                    @if ($report->status === 'Pelaksanaan')
                    <button type="button" class="btn btn-warning flex-grow-1 fw-bold"
                        onclick="confirmStatus('Ajukan Pemeriksaan', 'form-to-pemeriksaan')">
                        <i class="fas fa-clipboard-check me-1"></i> Selesai & Periksa
                    </button>
                    <form id="form-to-pemeriksaan" method="POST"
                        action="{{ route('admin.laporan.update-status', [$report->id, 'Pemeriksaan']) }}"
                        class="d-none">@csrf</form>
                    @endif

                    {{-- 5. Tahap Pemeriksaan --}}
                    @if ($report->status === 'Pemeriksaan')
                    <button type="button" class="btn btn-success flex-grow-1 fw-bold"
                        onclick="confirmStatus('Selesaikan Laporan', 'form-to-selesai')">
                        <i class="fas fa-flag-checkered me-1"></i> Tandai Selesai
                    </button>
                    <form id="form-to-selesai" method="POST"
                        action="{{ route('admin.laporan.update-status', [$report->id, 'Selesai']) }}" class="d-none">
                        @csrf</form>
                    @endif

                </div>

                {{-- FOTO DOKUMENTASI --}}
                <div class="mb-4">
                    <label class="text-muted small text-uppercase fw-bold mb-2 d-block">
                        Dokumentasi Kerusakan
                    </label>

                    @if ($report->images->count() > 0)

                    <div id="carouselReport" class="carousel slide rounded shadow-sm bg-dark" data-bs-ride="carousel">

                        <div class="carousel-inner">

                            @foreach ($report->images as $key => $image)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="d-block w-100 rounded"
                                        style="max-height:450px; object-fit:contain;">
                                </a>
                            </div>
                            @endforeach

                        </div>

                        @if ($report->images->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselReport"
                            data-bs-slide="prev">

                            <span class="carousel-control-prev-icon"></span>

                        </button>

                        <button class="carousel-control-next" type="button" data-bs-target="#carouselReport"
                            data-bs-slide="next">

                            <span class="carousel-control-next-icon"></span>

                        </button>
                        @endif

                    </div>

                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Klik gambar untuk memperbesar • Total {{ $report->images->count() }} foto
                        </small>
                    </div>
                    @else
                    <div class="alert alert-light text-center border py-4">
                        <i class="fas fa-image fa-3x text-muted mb-2"></i>
                        <p class="mb-0 text-muted">Tidak ada foto dokumentasi</p>
                    </div>

                    @endif
                </div>


                {{-- JUDUL --}}
                <div class="mb-4">
                    <label class="text-muted small text-uppercase fw-bold">
                        Judul Laporan
                    </label>

                    <h4 class="fw-bold text-dark mb-0">
                        {{ $report->judul }}
                    </h4>
                </div>


                {{-- JENIS USULAN --}}
                <div class="mb-4">
                    <div class="p-3 border rounded bg-light shadow-sm">

                        <label class="text-muted small text-uppercase fw-bold d-block mb-2">
                            <i class="fas fa-paper-plane me-1 text-primary"></i>
                            Jenis Usulan / Perihal
                        </label>

                        <p class="mb-0 fw-semibold text-dark">
                            {{ $report->jenis_usulan ?? 'Tidak ada keterangan jenis usulan' }}
                        </p>

                    </div>
                </div>

                {{-- Kategori Usulan --}}
                <div class="mb-4">
                    <div class="p-3 border rounded bg-light shadow-sm">

                        <label class="text-muted small text-uppercase fw-bold d-block mb-2">
                            <i class="fas fa-paper-plane me-1 text-primary"></i>
                            Kategori Usulan
                        </label>

                        <p class="mb-0 fw-semibold text-dark">
                            {{ $report->kategori ?? 'Tidak ada keterangan jenis usulan' }}
                        </p>

                    </div>
                </div>
                @php
                // Ambil sub-kategori dari kolom kategori jika formatnya masih 'Parent - Sub'
                // Jika kolom kategori kosong, defaultnya null
                $katEksplode = explode(' - ', $report->kategori ?? '');
                $subKat = $katEksplode[1] ?? null;
                @endphp

                {{-- DETAIL BAGIAN --}}
                @if ($subKat)
                <div class="mb-4">
                    <label class="text-muted small text-uppercase fw-bold mb-2 d-block">
                        <i class="fas fa-search me-1 text-info"></i> Detail Bagian
                    </label>

                    <span class="badge bg-info fs-6 px-3 py-2 shadow-sm">
                        {{ $subKat }}
                    </span>
                </div>
                @endif


                {{-- LOKASI --}}
                <div class="mb-4 p-3 bg-light rounded border-start border-4 border-warning shadow-sm">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <label class="text-muted small text-uppercase fw-bold">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                Lokasi Kejadian
                            </label>

                            <p class="mb-0 fw-bold text-dark">
                                {{ $report->lokasi }}
                            </p>
                        </div>

                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($report->lokasi) }}"
                            target="_blank" class="btn btn-sm btn-outline-secondary">

                            <i class="fas fa-map"></i> Cek Map

                        </a>

                    </div>

                </div>


                {{-- DESKRIPSI --}}
                <div class="mb-2">

                    <label class="text-muted small text-uppercase fw-bold">
                        Deskripsi Laporan
                    </label>

                    <p class="text-dark lh-lg">
                        {{ $report->deskripsi }}
                    </p>

                </div>

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
                    <span class="badge bg-success-subtle text-success border border-success px-3">TERVERIFIKASI</span>
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

<script>
    const displayInput = document.getElementById('nominal_rab_display');
        const realInput = document.getElementById('nominal_rab_real');

        displayInput.addEventListener('input', function(e) {
            // 1. Ambil angka saja, hapus semua karakter non-digit
            let rawValue = this.value.replace(/\D/g, '');

            // 2. Simpan angka bersih ke input hidden (untuk database)
            realInput.value = rawValue;

            // 3. Format angka dengan titik untuk tampilan
            if (rawValue) {
                this.value = formatRupiah(rawValue);
            } else {
                this.value = '';
            }
        });

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }
</script>

@endsection