<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | SOC (SDA ON CALL)</title>

    {{-- Bootstrap 5.3.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Google Font: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --soc-yellow: #ffc107;
            --soc-blue: #0d47a1;
            --soc-dark-blue: #0a192f;
            --bg-light: #f4f7fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            background-image: radial-gradient(var(--soc-yellow) 0.5px, transparent 0.5px);
            background-size: 30px 30px;
            background-attachment: fixed;
            padding: 40px 0;
            color: var(--soc-dark-blue);
        }

        .register-card {
            max-width: 850px;
            margin: auto;
            background: #fff;
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .register-header {
            background: var(--soc-dark-blue);
            padding: 3rem 2rem;
            text-align: center;
            color: #fff;
            position: relative;
        }

        /* Dekorasi Header */
        .register-header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--soc-yellow);
        }

        .brand-badge {
            display: inline-block;
            background: var(--soc-yellow);
            color: var(--soc-dark-blue);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--soc-dark-blue);
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #6c757d;
        }

        .form-control, .form-select {
            padding: 0.7rem 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #e0e0e0;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--soc-yellow);
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.2);
        }

        .section-title {
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--soc-blue);
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .section-title i {
            margin-right: 10px;
            font-size: 1.1rem;
            color: var(--soc-yellow);
        }

        .btn-register {
            background: var(--soc-yellow);
            border: none;
            padding: 1rem;
            font-weight: 700;
            color: var(--soc-dark-blue);
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-register:hover {
            background: #e5ae06;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 193, 7, 0.3);
            color: var(--soc-dark-blue);
        }

        .file-upload-wrapper {
            position: relative;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
        }

        .file-upload-wrapper:hover {
            border-color: var(--soc-yellow);
            background: #fffef0;
        }

        .login-link {
            color: var(--soc-blue);
            font-weight: 700;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="register-card">
            <div class="register-header">
                <div class="brand-badge">SOC SYSTEM</div>
                <h2 class="fw-extrabold mb-1">Registrasi Akun</h2>
                <p class="mb-0 opacity-75">SDA ON CALL - Portal Pelaporan Masyarakat & Internal</p>
            </div>

            <div class="p-4 p-md-5">
                @if($errors->any())
                <div class="alert alert-danger border-0 mb-4 shadow-sm" style="border-left: 5px solid #dc3545 !important;">
                    <ul class="mb-0 small fw-medium">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ url('/register') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="section-title"><i class="bi bi-person-vcard-fill"></i> Informasi Identitas</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIK (Sesuai KTP)</label>
                            <input type="text" name="nik" class="form-control" placeholder="Masukkan 16 digit NIK" value="{{ old('nik') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama tanpa gelar" value="{{ old('name') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control" placeholder="Contoh: Karyawan Swasta" value="{{ old('pekerjaan') }}" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="section-title"><i class="bi bi-shield-lock-fill"></i> Kontak & Keamanan</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" name="nomor_wa" class="form-control" placeholder="812xxxx" value="{{ old('nomor_wa') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-12">
                            <h6 class="section-title"><i class="bi bi-geo-alt-fill"></i> Alamat & Verifikasi</h6>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label">Alamat Lengkap (Sesuai KTP)</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan..." required>{{ old('alamat') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Unggah Foto KTP</label>
                            <div class="file-upload-wrapper">
                                <i class="bi bi-cloud-arrow-up-fill fs-1 text-muted"></i>
                                <p class="mb-2 small fw-bold">Klik atau seret file ke sini</p>
                                <input type="file" name="foto_ktp" class="form-control" accept="image/*" required>
                                <div class="form-text mt-2 text-danger fw-medium">Format: JPG, PNG (Maksimal 2MB)</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-register">
                            Daftar Akun SOC <i class="bi bi-check-circle-fill ms-2"></i>
                        </button>
                        <div class="text-center">
                            <p class="small text-muted mb-0">Sudah memiliki akun?</p>
                            <a href="{{ url('/') }}" class="login-link">Kembali ke Halaman Login</a>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="bg-light p-3 text-center border-top">
                <small class="text-muted">Layanan Pengaduan Sumber Daya Air - &copy; {{ date('Y') }}</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>