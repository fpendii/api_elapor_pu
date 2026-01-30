<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | Pelaporan Masyarakat</title>

    {{-- Bootstrap 5.3.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Google Font: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            /* Tema Kuning Utama */
            --brand-yellow: #ffc107;
            --brand-yellow-dark: #eab308;
            --brand-yellow-light: #fefce8;
            --sidebar-gradient: linear-gradient(180deg, #ffc107 0%, #fbbf24 100%);
            --text-dark: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: var(--text-dark);
        }

        /* SIDEBAR - Tetap Kuning Sesuai Request */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--sidebar-gradient);
            z-index: 1030;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
        }

        .sidebar .brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .nav-custom {
            padding: 1.2rem 0.8rem;
        }

        .nav-custom .nav-link {
            color: rgba(30, 41, 59, 0.8);
            /* Teks gelap transparan agar elegan */
            font-weight: 500;
            padding: 0.8rem 1.2rem;
            border-radius: 10px;
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
        }

        .nav-custom .nav-link i {
            font-size: 1.1rem;
        }

        .nav-custom .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.3);
            color: var(--text-dark);
            transform: translateX(5px);
        }

        .nav-custom .nav-link.active {
            background-color: white;
            /* Menu aktif warna putih agar kontras dengan kuning */
            color: var(--brand-yellow-dark);
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* CONTENT AREA */
        .content-wrapper {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* TOPBAR */
        .topbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.8rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        /* USER PROFILE STYLING */
        .user-link {
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .user-link:hover {
            opacity: 0.8;
        }

        .avatar-box {
            width: 38px;
            height: 38px;
            background: var(--text-dark);
            /* Kontras dengan kuning */
            color: var(--brand-yellow);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* MAIN CONTENT */
        main {
            padding: 2rem;
        }

        /* BUTTONS */
        .btn-warning {
            background-color: var(--brand-yellow);
            border-color: var(--brand-yellow);
            font-weight: 600;
        }

        .btn-warning:hover {
            background-color: var(--brand-yellow-dark);
            border-color: var(--brand-yellow-dark);
        }

        .modal-content {
            border-radius: 20px;
        }

        #logoutModal .btn {
            border-radius: 12px;
            padding: 10px 20px;
        }

        /* Animasi halus saat modal muncul */
        .modal.fade .modal-dialog {
            transform: scale(0.9);
            transition: transform 0.2s ease-out;
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }

        @media (max-width: 992px) {
            .sidebar {
                margin-left: -260px;
                position: fixed;
            }

            .content-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar position-fixed">
        <div class="brand">
            <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-megaphone-fill"></i> LaporPak!
            </h5>
            <small class="opacity-75 fw-medium">Panel Administrator</small>
        </div>

        <div class="nav-custom">
            <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}"
                href="{{ url('admin/dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a class="nav-link {{ request()->is('admin/laporan*') ? 'active' : '' }}" href="{{ url('admin/laporan') }}">
                <i class="bi bi-chat-left-dots"></i>
                <span>Laporan</span>
            </a>

            <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
                href="{{ url('admin/users/verifikasi') }}">
                <i class="bi bi-people"></i>
                <span>Pengguna</span>
            </a>

            <hr class="opacity-10 my-4">

            <a class="nav-link {{ request()->is('admin/profile*') ? 'active' : '' }}"
                href="{{ route('admin.profile.index') }}">
                <i class="bi bi-person-circle"></i>
                <span>Profil Saya</span>
            </a>
        </div>
    </aside>

    {{-- CONTENT --}}
    <div class="content-wrapper">

        {{-- TOPBAR --}}
        <nav class="navbar topbar">
            <div class="container-fluid">
                <span class="navbar-text fw-bold text-dark fs-5">
                    @yield('page-title', 'Dashboard')
                </span>

                <div class="d-flex align-items-center gap-3">
                    {{-- Profile Info --}}
                    <a href="{{ route('admin.profile.index') }}" class="user-link d-flex align-items-center gap-3">
                        <div class="text-end d-none d-sm-block">
                            <div class="text-dark fw-bold small" style="line-height: 1;">{{ auth()->user()->name }}
                            </div>
                            <small class="text-muted"
                                style="font-size: 0.75rem;">{{ ucfirst(auth()->user()->role) }}</small>
                        </div>
                        <div class="avatar-box">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </a>

                    <div class="vr mx-1 opacity-25" style="height: 25px;"></div>

                    {{-- Logout --}}
                    {{-- GANTI TOMBOL LOGOUT DI TOPBAR DENGAN INI --}}
                    <button type="button" data-bs-toggle="modal" data-bs-target="#logoutModal"
                        class="btn btn-link text-danger p-0 border-0">
                        <i class="bi bi-box-arrow-right fs-4"></i>
                    </button>


                </div>
            </div>
        </nav>

        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
            @csrf
        </form>

        {{-- MAIN --}}
        <main>
            @yield('content')
        </main>

    </div>
    {{-- TAMBAHKAN MODAL LOGOUT DI BAGIAN BAWAH (Sebelum </body>) --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-0 pb-4">
                    {{-- Icon Logout Besar --}}
                    <div class="mb-3">
                        <div class="bg-light-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px; background-color: #fff5f5;">
                            <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-2">Konfirmasi Logout</h5>
                    <p class="text-muted">Apakah Anda yakin ingin mengakhiri sesi ini dan keluar dari
                        sistem?</p>

                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button type="button" class="btn btn-light px-4 fw-semibold"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="button" onclick="document.getElementById('logout-form').submit();"
                            class="btn btn-warning px-4 fw-bold text-dark shadow-sm">
                            Ya, Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
