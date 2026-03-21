@extends('layouts.admin')

@section('title', 'Jenis DAP')
@section('page-title', 'Manajemen Jenis DAP')

@section('content')

{{-- Alert Success --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- FORM TAMBAH JENIS RAB --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-plus-circle me-2 text-warning"></i>Tambah Jenis DAP Baru</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.jenis-dap.store') }}" onsubmit="return confirmSubmit(this, 'Simpan Jenis DAP baru?')">
            @csrf
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted">Nama DAP / Pekerjaan</label>
                    <input name="nama_rab" class="form-control" placeholder="Contoh: Perbaikan Aspal Jalan" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">Estimasi Dana</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">Rp</span>
                        <input name="dana" class="form-control border-start-0" placeholder="50.000.000" required>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-warning w-100 fw-bold">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- TABEL DATA JENIS RAB --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" width="50">No</th>
                        <th>Nama Jenis DAP</th>
                        <th>Estimasi Dana</th>
                        <th class="text-center" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisRabs as $index => $rab)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                        <td><span class="fw-bold text-dark">{{ $rab->nama_rab }}</span></td>
                        <td><span class="text-success fw-medium">Rp {{ $rab->dana }}</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                {{-- Tombol Edit (Trigger Modal) --}}
                                <button type="button" class="btn btn-sm btn-light text-primary border" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal{{ $rab->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form method="POST" action="{{ route('admin.jenis-dap.destroy', $rab->id) }}" class="d-inline" onsubmit="return confirmDelete(this)">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger border">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL EDIT PER BARIS --}}
                    <div class="modal fade" id="editModal{{ $rab->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-bottom-0 pt-4 px-4">
                                    <h5 class="fw-bold mb-0">Edit Jenis RAB</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.jenis-dap.update', $rab->id) }}" method="POST" onsubmit="return confirmSubmit(this, 'Simpan perubahan data ini?')">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">Nama RAB</label>
                                            <input type="text" name="nama_rab" class="form-control" value="{{ $rab->nama_rab }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">Estimasi Dana</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">Rp</span>
                                                <input type="text" name="dana" class="form-control" value="{{ $rab->dana }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pb-4 px-4">
                                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning fw-bold px-4">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- END MODAL --}}

                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT KONFIRMASI SWEETALERT2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfirmasi Hapus
    function confirmDelete(form) {
        event.preventDefault();
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Konfirmasi Simpan/Update
    function confirmSubmit(form, message) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Konfirmasi Perubahan Status (Fungsi Lama Anda)
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