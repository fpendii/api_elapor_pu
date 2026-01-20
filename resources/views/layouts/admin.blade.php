<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f8;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, #ffc107, #ffca2c);
        }

        .sidebar .brand {
            padding: 1.2rem;
            border-bottom: 1px solid rgba(0,0,0,.1);
        }

        .sidebar .nav-link {
            color: #212529;
            border-radius: .6rem;
            padding: .6rem .9rem;
            margin-bottom: .2rem;
            transition: all .2s ease;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(0,0,0,.08);
        }

        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,.7);
            font-weight: 600;
        }

        /* Content */
        .content-wrapper {
            margin-left: 250px;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            background-color: #fff;
            border-bottom: 1px solid #e5e7eb;
        }

        /* Main content */
        main {
            padding: 1.5rem;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar position-fixed">
    <div class="brand">
        <h5 class="mb-0 fw-semibold">Admin Panel</h5>
        <small class="text-muted">Pelaporan Masyarakat</small>
    </div>

    <ul class="nav flex-column p-3">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}"
               href="{{ url('admin/dashboard') }}">
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/laporan*') ? 'active' : '' }}"
               href="{{ url('admin/laporan') }}">
                Laporan
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
               href="{{ url('admin/users/verifikasi') }}">
                Pengguna
            </a>
        </li>
    </ul>
</aside>

{{-- CONTENT --}}
<div class="content-wrapper">

    {{-- TOPBAR --}}
    <nav class="navbar topbar px-4 py-2">
        <span class="fw-semibold text-dark">
            @yield('page-title', 'Admin')
        </span>

        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">{{ auth()->user()->name }}</span>

            <a href="{{ route('logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();"
               class="btn btn-sm btn-outline-danger">
                Logout
            </a>
        </div>
    </nav>

    <form id="logout-form" method="POST" action="{{ route('logout') }}">
        @csrf
    </form>

    {{-- MAIN --}}
    <main>
        @yield('content')
    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
