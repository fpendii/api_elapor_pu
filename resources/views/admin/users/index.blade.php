    @extends('layouts.admin')

    @section('title', 'Pengguna')
    @section('page-title', 'Manajemen Pengguna')

    @section('content')
    <div class="container-fluid py-2">

        {{-- HEADER SECTION --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-white p-4 shadow-sm border-0 d-md-flex justify-content-between align-items-center" style="border-radius: 20px;">
                    <div>
                        <h3 class="fw-800 text-dark mb-1">Manajemen Pengguna</h3>
                        <p class="text-muted mb-0">Kelola data penduduk dan administrator sistem dalam satu panel.</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-600">
                            Total: {{ $users->total() }} Pengguna
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
        @endif

        {{-- FORM TAMBAH USER (Modern Style) --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 24px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-800 mb-0"><i class="bi bi-person-plus-fill text-warning me-2"></i>Tambah Pengguna Baru</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="small fw-600 text-muted mb-1">NIK</label>
                            <input name="nik" class="form-control custom-input" placeholder="Masukkan NIK" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-600 text-muted mb-1">Nama Lengkap</label>
                            <input name="name" class="form-control custom-input" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-600 text-muted mb-1">Email</label>
                            <input type="email" name="email" class="form-control custom-input" placeholder="email@contoh.com" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-600 text-muted mb-1">Password</label>
                            <input type="password" name="password" class="form-control custom-input" placeholder="********" required>
                        </div>

                        <div class="col-md-2">
                            <label class="small fw-600 text-muted mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select custom-input">
                                <option value="">Pilih JK</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-600 text-muted mb-1">Pekerjaan</label>
                            <input name="pekerjaan" class="form-control custom-input" placeholder="Contoh: PNS, Swasta">
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-600 text-muted mb-1">No. WhatsApp</label>
                            <input name="nomor_wa" class="form-control custom-input" placeholder="0812xxxx">
                        </div>
                        <div class="col-md-2">
                            <label class="small fw-600 text-muted mb-1">Role</label>
                            <select name="role" class="form-select custom-input" required>
                                <option value="user">Masyarakat (User)</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small fw-600 text-muted mb-1">Foto KTP</label>
                            <input type="file" name="foto_ktp" class="form-control custom-input">
                        </div>

                        <div class="col-md-10">
                            <label class="small fw-600 text-muted mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control custom-input" rows="1" placeholder="Jl. Raya Nomor..."></textarea>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-warning w-100 fw-800 text-dark py-2 shadow-sm" style="border-radius: 12px;">
                                <i class="bi bi-plus-lg me-1"></i> SIMPAN
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABEL PENGGUNA --}}
        <div class="card border-0 shadow-sm" style="border-radius: 24px; overflow: hidden;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light-subtle border-bottom border-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted fw-600 small">PENGGUNA</th>
                                <th class="py-3 text-muted fw-600 small">ROLE</th>
                                <th class="py-3 text-muted fw-600 small">KONTAK</th>
                                <th class="py-3 text-muted fw-600 small">KTP</th>
                                <th class="py-3 text-muted fw-600 small text-center pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light text-dark fw-bold d-flex align-items-center justify-content-center me-3" 
                                            style="width: 40px; height: 40px; border-radius: 12px; font-size: 0.9rem;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0">{{ $user->name }}</div>
                                            <div class="text-muted small">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $user->role == 'admin' ? 'bg-light-primary text-primary' : 'bg-light-secondary text-secondary' }} px-3 py-2 rounded-pill fw-600">
                                        {{ strtoupper($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-600 small text-dark"><i class="bi bi-whatsapp text-success me-1"></i>{{ $user->nomor_wa ?? '-' }}</div>
                                </td>
                                <td>
                                    @if($user->foto_ktp)
                                        <a href="{{ asset('storage/'.$user->foto_ktp) }}" target="_blank" class="btn btn-light btn-sm rounded-3 fw-600">
                                            <i class="bi bi-image me-1"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-muted small italic">Belum Upload</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-light text-danger btn-sm rounded-3 px-3 shadow-none border-0" 
                                                onclick="return confirm('Hapus user ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($users->hasPages())
            <div class="card-footer bg-white border-0 p-4">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>

    <style>
        /* Typography */
        .fw-600 { font-weight: 600; }
        .fw-800 { font-weight: 800; }
        
        /* Soft Colors */
        .bg-light-primary { background-color: #f0f3ff; color: #4e73df; }
        .bg-light-secondary { background-color: #f8f9fc; color: #858796; }

        /* Custom Input Style */
        .custom-input {
            border: 1px solid #f0f0f0;
            background-color: #fbfbfb;
            border-radius: 12px;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .custom-input:focus {
            background-color: #fff;
            border-color: #ffc107;
            box-shadow: 0 0 0 0.25 margin-bottom: 20px;rem rgba(255, 193, 7, 0.1);
        }

        /* Table Hover */
        .table-hover tbody tr:hover {
            background-color: #fdfdfd;
        }

        /* Pagination Styling */
        .pagination { margin-bottom: 0; gap: 5px; }
        .page-item .page-link { 
            border: none; 
            border-radius: 8px; 
            color: #666;
            font-weight: 600;
        }
        .page-item.active .page-link { background-color: #ffc107; color: #000; }
    </style>
    @endsection