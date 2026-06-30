<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - SIPDA Bandung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sipda-theme.css') }}">
    @stack('styles')
    <script>
        (function() {
            var t = localStorage.getItem('sipda-theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>
    <style>* { margin: 0; padding: 0; box-sizing: border-box; }</style>
</head>
<body>

<div class="d-flex" style="min-height:100vh;">

    {{-- SIDEBAR --}}
    <div class="sidebar sidebar-admin flex-shrink-0 d-flex flex-column" style="width:240px;">

        {{-- Logo / Brand --}}
        <div class="sidebar-header">
            <div class="sidebar-header-logo mx-auto">
                <span class="material-symbols-outlined msf text-warning ms-lg">security</span>
            </div>
            <div class="text-white fw-bold" style="font-size:0.92rem;line-height:1.3;">SIPDA Admin</div>
            <div class="text-white opacity-40 mt-1" style="font-size:0.7rem;">Sistem Peringatan Bencana</div>
        </div>

        {{-- User info --}}
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->user()->full_name }}</div>
                <span class="badge bg-danger" style="font-size:0.6rem;padding:2px 7px;border-radius:4px;">Administrator</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-grow-1 py-1">
            <div class="sidebar-section-label">Navigasi</div>
            <ul class="nav flex-column px-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="material-symbols-outlined ms-sm">dashboard</span>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.bencana.*') ? 'active' : '' }}" href="{{ route('admin.bencana.index') }}">
                        <span class="material-symbols-outlined ms-sm">warning</span>Data Bencana
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}" href="{{ route('admin.berita.index') }}">
                        <span class="material-symbols-outlined ms-sm">newspaper</span>Berita
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.index') }}">
                        <span class="material-symbols-outlined ms-sm">chat</span>Laporan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <span class="material-symbols-outlined ms-sm">group</span>Kelola User
                    </a>
                </li>
            </ul>

            <div class="sidebar-section-label">Akun</div>
            <ul class="nav flex-column px-1">
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                            <span class="material-symbols-outlined ms-sm">logout</span>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        {{-- Bottom actions --}}
        <div class="sidebar-bottom">
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                <span class="material-symbols-outlined ms-sm">home</span>Ke Beranda
            </a>
            <button class="theme-toggle btn d-flex align-items-center gap-2" id="themeToggle" title="Ganti tema">
                <span class="material-symbols-outlined ms-sm theme-icon-dark">dark_mode</span>
                <span class="material-symbols-outlined ms-sm theme-icon-light">light_mode</span>
                <span class="theme-label-dark">Mode Gelap</span>
                <span class="theme-label-light">Mode Terang</span>
            </button>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="flex-grow-1 dashboard-wrap p-4" style="min-width:0;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined msf ms-sm">check_circle</span>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined msf ms-sm">error</span>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script>
    (function() {
        const toggle = document.getElementById('themeToggle');
        if (toggle) {
            toggle.addEventListener('click', function () {
                const html = document.documentElement;
                const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-bs-theme', next);
                localStorage.setItem('sipda-theme', next);
            });
        }
    })();
</script>
@stack('scripts')
</body>
</html>
