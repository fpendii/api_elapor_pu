@extends('layouts.admin')

@section('title', 'Jenis DAP')
@section('page-title', 'Manajemen Kategori Kegiatan')

@section('content')

{{-- Alert Success --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-bold">Daftar Kategori Kegiatan</h5>
    {{-- Tombol Trigger Modal Tambah --}}
    <button type="button" class="btn btn-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
        <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
    </button>
</div>

{{-- TABEL DATA JENIS RAB --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" width="50">No</th>
                        <th>Kategori Kegiatan</th>
                        <th>Estimasi Dana</th>
                        <th class="text-center" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisRabs as $index => $rab)
                    <tr>
                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                        <td><span class="fw-bold text-dark">{{ $rab->nama_rab }}</span></td>
                        <td><span class="text-success fw-medium">Rp {{ number_format($rab->dana, 0, ',', '.') }}</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button" class="btn btn-sm btn-light text-primary border" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal{{ $rab->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form method="POST" action="{{ route('admin.kategori-kegiatan.destroy', $rab->id) }}" class="d-inline" onsubmit="return confirmDelete(this)">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger border">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="editModal{{ $rab->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-bottom-0 pt-4 px-4">
                                    <h5 class="fw-bold mb-0">Edit Kategori Kegiatan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.kategori-kegiatan.update', $rab->id) }}" method="POST" onsubmit="return prepareAndConfirm(this, 'Simpan perubahan data ini?')">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">Nama Kategori</label>
                                            <input type="text" name="nama_rab" class="form-control" value="{{ $rab->nama_rab }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">Estimasi Dana</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">Rp</span>
                                                <input type="text" name="dana" class="form-control rupiah" value="{{ number_format($rab->dana, 0, ',', '.') }}" required>
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

{{-- MODAL TAMBAH DATA --}}
<div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.kategori-kegiatan.store') }}" onsubmit="return prepareAndConfirm(this, 'Simpan Kategori Kegiatan Baru?')">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Kategori Kegiatan</label>
                        <input name="nama_rab" class="form-control" placeholder="Contoh: Perbaikan Aspal Jalan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Estimasi Dana</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input name="dana" class="form-control rupiah" placeholder="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Tambah Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. Fungsi Format Rupiah (Real-time)
    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    }

    // Event Listener untuk semua input dengan class .rupiah
    document.addEventListener('keyup', function(e) {
        if (e.target.classList.contains('rupiah')) {
            e.target.value = formatRupiah(e.target.value);
        }
    });

    // 2. Fungsi Submit (Tambah & Edit)
   function prepareAndConfirm(form, message) {
    // 1. Cegah submit otomatis
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
            // 2. BERSIHKAN TITIK: Ambil semua elemen dengan class .rupiah di DALAM form ini
            const inputs = form.querySelectorAll('.rupiah');
            
            inputs.forEach(input => {
                // Hapus semua karakter yang bukan angka (termasuk titik)
                // Kita simpan ke variabel dulu, lalu timpa nilainya
                let cleanValue = input.value.replace(/\./g, '');
                input.value = cleanValue;
            });

            // 3. Submit form setelah data bersih
            form.submit();
        }
    });
}

    // 3. Konfirmasi Hapus
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
</script>

@endsection