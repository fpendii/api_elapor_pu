@extends('layouts.admin')

@section('title', 'Profil Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')

{{-- Alert Success --}}
@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

<div class="row g-4">
    
    {{-- SISI KIRI: PROFIL RINGKAS & VERIFIKASI --}}
    <div class="col-xl-4 col-lg-5">
        
        {{-- Card Foto KTP --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3 text-center">
                <h6 class="fw-bold mb-0">Dokumen Identitas</h6>
            </div>
            <div class="card-body pt-0 text-center">
                <div class="bg-light rounded-3 p-2 mb-3">
                    @if($user->foto_ktp)
                        <img src="{{ asset('storage/'.$user->foto_ktp) }}" 
                             class="img-fluid rounded-3 shadow-sm cursor-pointer" 
                             style="max-height: 220px; width: 100%; object-fit: cover;"
                             data-bs-toggle="modal" data-bs-target="#ktpModal">
                        <small class="text-muted d-block mt-2">Klik gambar untuk memperbesar</small>
                    @else
                        <div class="py-5">
                            <i class="bi bi-person-bounding-box display-4 text-secondary opacity-25"></i>
                            <p class="text-muted small mt-2">Belum ada foto KTP</p>
                        </div>
                    @endif
                </div>

                {{-- Status Badge --}}
                @php
                    $statusColor = match($user->verifikasi) {
                        'acc' => 'success',
                        'menunggu' => 'warning',
                        default => 'danger'
                    };
                @endphp
                <div class="d-inline-block px-4 py-2 rounded-pill bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} fw-bold mb-2">
                    <i class="bi bi-patch-check-fill me-1"></i>
                    {{ strtoupper($user->verifikasi) }}
                </div>
            </div>
        </div>

        {{-- Card Action Verifikasi --}}
        @if($user->verifikasi === 'menunggu')
        <div class="card border-0 shadow-sm bg-dark text-white">
            <div class="card-body p-4 text-center">
                <h6 class="fw-bold mb-3">Tindakan Verifikasi</h6>
                <p class="small opacity-75 mb-4">Pastikan data yang diinput sesuai dengan dokumen KTP yang dilampirkan.</p>
                <div class="d-grid gap-2">
                    <form method="POST" action="{{ route('admin.users.verifikasi.acc', $user->id) }}">
                        @csrf
                        <button class="btn btn-warning w-100 fw-bold py-2">Setujui Akun</button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.verifikasi.tolak', $user->id) }}">
                        @csrf
                        <button class="btn btn-outline-light w-100 btn-sm">Tolak Verifikasi</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- SISI KANAN: DETAIL INFORMASI --}}
    <div class="col-xl-8 col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Informasi Detail</h5>
                <a href="{{ route('admin.users.verifikasi.index') }}" class="btn btn-sm btn-light border text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            
            <div class="card-body p-4">
                <div class="row g-4">
                    {{-- Section Identitas --}}
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning p-2 rounded-3 me-3">
                                <i class="bi bi-person-vcard text-dark fs-5"></i>
                            </div>
                            <h6 class="mb-0 fw-bold">Data Identitas</h6>
                        </div>
                    </div>
                    
                    <div class="col-sm-6">
                        <label class="text-muted small d-block">Nama Lengkap</label>
                        <span class="fw-semibold text-dark">{{ $user->name }}</span>
                    </div>
                    
                    <div class="col-sm-6">
                        <label class="text-muted small d-block">NIK</label>
                        <span class="fw-semibold text-dark">{{ $user->nik }}</span>
                    </div>

                    <div class="col-sm-6">
                        <label class="text-muted small d-block">Jenis Kelamin</label>
                        <span class="fw-semibold text-dark">{{ $user->jenis_kelamin ?? '-' }}</span>
                    </div>

                    <div class="col-sm-6">
                        <label class="text-muted small d-block">Pekerjaan</label>
                        <span class="fw-semibold text-dark">{{ $user->pekerjaan }}</span>
                    </div>

                    <hr class="my-2 opacity-5">

                    {{-- Section Kontak --}}
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning p-2 rounded-3 me-3">
                                <i class="bi bi-envelope-at text-dark fs-5"></i>
                            </div>
                            <h6 class="mb-0 fw-bold">Kontak & Alamat</h6>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <label class="text-muted small d-block">Email</label>
                        <span class="fw-semibold text-dark">{{ $user->email }}</span>
                    </div>

                    <div class="col-sm-6">
                        <label class="text-muted small d-block">No. WhatsApp</label>
                        <span class="fw-semibold text-dark text-success">
                            <i class="bi bi-whatsapp me-1"></i>{{ $user->nomor_wa }}
                        </span>
                    </div>

                    <div class="col-12">
                        <label class="text-muted small d-block">Alamat Lengkap</label>
                        <span class="fw-semibold text-dark d-block p-3 bg-light rounded-3 mt-1">
                            {{ $user->alamat }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Zoom KTP --}}
<div class="modal fade" id="ktpModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-1 text-center bg-dark rounded overflow-hidden">
                <img src="{{ asset('storage/'.$user->foto_ktp) }}" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

@endsection
