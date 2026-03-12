<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | Pelaporan Masyarakat</title>

    {{-- Bootstrap 5.3.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Google Font: Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand-yellow: #ffc107;
            --brand-dark: #1e293b;
            --sidebar-bg: #ffffff; /* Sidebar Putih Bersih */
            --body-bg: #f1f5f9;    /* Abu-abu sangat muda untuk background */
            --accent-light: #fffbeb; /* Kuning sangat muda untuk hover */
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--body-bg);
            color: var(--brand-dark);
        }

        /* --- SIDEBAR MINIMALIS --- */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            border-right: 1px solid #e2e8f0;
            z-index: 1030;
        }

        .sidebar .brand {
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 35px;
            height: 35px;
            background: var(--brand-yellow);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-dark);
            font-size: 1.2rem;
        }

        .nav-custom {
            padding: 0.5rem 1rem;
        }

        .nav-custom .nav-link {
            color: #64748b;
            font-weight: 500;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
        }

        .nav-custom .nav-link i {
            font-size: 1.2rem;
        }

        .nav-custom .nav-link:hover {
            background-color: var(--accent-light);
            color: var(--brand-dark);
        }

        .nav-custom .nav-link.active {
            background-color: var(--brand-yellow);
            color: var(--brand-dark);
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.2);
        }

        /* --- CONTENT AREA --- */
        .content-wrapper {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* --- TOPBAR CLEAN --- */
        .topbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        /* --- USER BOX --- */
        .avatar-box {
            width: 38px;
            height: 38px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--brand-dark);
        }

        /* --- MAIN CONTENT --- */
        main {
            padding: 2rem;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .btn-warning {
            background-color: var(--brand-yellow);
            border: none;
            font-weight: 600;
            border-radius: 10px;
        }

        .btn-warning:hover {
            background-color: #eab308;
            transform: translateY(-1px);
        }

        @media (max-width: 992px) {
            .sidebar { margin-left: -260px; position: fixed; }
            .content-wrapper { margin-left: 0; }
        }
    </style>
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar position-fixed">
        <div class="brand">
            <div class="brand-icon">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <h5 class="mb-0 fw-bold text-dark tracking-tight">LaporPak!</h5>
        </div>

        <div class="nav-custom">
            <small class="text-uppercase text-muted fw-bold mb-3 d-block ms-2" style="font-size: 0.65rem; letter-spacing: 1px;">Menu Utama</small>
            
            <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}" href="{{ url('admin/dashboard') }}">
                <i class="bi bi-house-door"></i>
                <span>Dashboard</span>
            </a>
            <a class="nav-link {{ request()->is('admin/jenis-rab*') ? 'active' : '' }}" href="{{ url('admin/jenis-rab') }}">
                <i class="bi bi-list-task"></i>
                <span>Jenis RAB</span>
            </a>

            <a class="nav-link {{ request()->is('admin/laporan*') ? 'active' : '' }}" href="{{ url('admin/laporan') }}">
                <i class="bi bi-card-list"></i>
                <span>Daftar Laporan</span>
            </a>

            <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ url('admin/users/verifikasi') }}">
                <i class="bi bi-people"></i>
                <span>Manajemen User</span>
            </a>

            <hr class="my-4 opacity-50">

            <small class="text-uppercase text-muted fw-bold mb-3 d-block ms-2" style="font-size: 0.65rem; letter-spacing: 1px;">Personal</small>

            <a class="nav-link {{ request()->is('admin/profile*') ? 'active' : '' }}" href="{{ route('admin.profile.index') }}">
                <i class="bi bi-person-circle"></i>
                <span>Profil</span>
            </a>
            
            <a class="nav-link text-danger mt-5" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="bi bi-box-arrow-left"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    {{-- CONTENT --}}
    <div class="content-wrapper">

        {{-- TOPBAR --}}
        <nav class="navbar topbar">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <span class="fw-bold text-dark fs-5">@yield('page-title')</span>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="text-end d-none d-sm-block">
                        <div class="text-dark fw-bold small" style="line-height: 1;">{{ auth()->user()->name }}</div>
                        <small class="text-muted" style="font-size: 0.75rem;">Admin Sistem</small>
                    </div>
                    <div class="avatar-box">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </nav>

        {{-- MAIN --}}
        <main>
            @yield('content')
        </main>

    </div>

    {{-- MODAL LOGOUT --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <h6 class="fw-bold mb-3">Yakin ingin keluar?</h6>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm px-3">Ya, Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>