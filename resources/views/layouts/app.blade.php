<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPDA Bandung') - Sistem Informasi Peringatan Dini Bencana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="{{ asset('css/sipda-theme.css') }}">
    @stack('styles')
    <script>
        (function() {
            var t = localStorage.getItem('sipda-theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-sipda navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div class="brand-icon-wrap">
                <span class="material-symbols-outlined msf text-warning ms-sm">security</span>
            </div>
            <span>SIPDA Bandung</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <span class="material-symbols-outlined ms-sm">home</span>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('bencana.*') ? 'active' : '' }}" href="{{ route('bencana.index') }}">
                        <span class="material-symbols-outlined ms-sm">warning</span>Bencana
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('berita.*') ? 'active' : '' }}" href="{{ route('berita.index') }}">
                        <span class="material-symbols-outlined ms-sm">newspaper</span>Berita
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('layanan') ? 'active' : '' }}" href="{{ route('layanan') }}">
                        <span class="material-symbols-outlined ms-sm">grid_view</span>Layanan
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-center gap-3">
                <li class="nav-item">
                    <button class="theme-toggle btn" id="themeToggle" title="Ganti tema">
                        <span class="material-symbols-outlined ms-sm" id="themeIcon">dark_mode</span>
                    </button>
                </li>
                @guest
                    <li class="nav-item d-none d-lg-flex align-items-center">
                        <span style="display:block;width:1px;height:22px;background:rgba(255,255,255,0.18);"></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <span class="material-symbols-outlined ms-sm">login</span>Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-warning btn-register" href="{{ route('register') }}">
                            <span class="material-symbols-outlined ms-sm">person_add</span>Daftar
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" data-bs-toggle="dropdown">
                            <span class="material-symbols-outlined msf ms-md">account_circle</span>
                            <span class="d-none d-lg-inline">{{ auth()->user()->full_name }}</span>
                            <span class="badge bg-{{ auth()->user()->role === 'admin' ? 'danger' : (auth()->user()->role === 'petugas' ? 'primary' : 'success') }} ms-1" style="font-size:0.65rem;">
                                {{ auth()->user()->role }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius:14px;min-width:200px;">
                            @if(auth()->user()->isAdmin())
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('admin.dashboard') }}">
                                        <span class="material-symbols-outlined ms-sm text-primary">dashboard</span>Dashboard Admin
                                    </a>
                                </li>
                            @elseif(auth()->user()->isPetugas())
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('petugas.dashboard') }}">
                                        <span class="material-symbols-outlined ms-sm text-primary">dashboard</span>Dashboard Petugas
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('user.dashboard') }}">
                                        <span class="material-symbols-outlined ms-sm text-primary">dashboard</span>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('user.laporan.create') }}">
                                        <span class="material-symbols-outlined ms-sm text-success">add_circle</span>Buat Laporan
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('user.profil') }}">
                                    <span class="material-symbols-outlined ms-sm text-secondary">manage_accounts</span>Profil Saya
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                                        <span class="material-symbols-outlined ms-sm">logout</span>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

{{-- Fixed flash toast — appears above all content, doesn't break page layout --}}
@if(session('success') || session('error'))
<div style="position:fixed;top:70px;left:50%;transform:translateX(-50%);z-index:1060;width:calc(100% - 2rem);max-width:480px;pointer-events:none;">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-lg" role="alert" style="pointer-events:auto;border-radius:14px;">
        <span class="material-symbols-outlined msf ms-sm flex-shrink-0">check_circle</span>
        {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-lg" role="alert" style="pointer-events:auto;border-radius:14px;">
        <span class="material-symbols-outlined msf ms-sm flex-shrink-0">error</span>
        {{ session('error') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif
</div>
@endif

<main>
    @yield('content')
</main>

<footer class="sipda-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="material-symbols-outlined msf text-warning ms-lg">security</span>
                    <h5 class="fw-bold mb-0">SIPDA Bandung</h5>
                </div>
                <p class="opacity-60 small mb-0">
                    Sistem Informasi Peringatan Dini Bencana Alam Kota Bandung — terintegrasi dengan BPBD Kota Bandung.
                </p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold mb-3">Tautan Cepat</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <a href="{{ route('bencana.index') }}" class="footer-link d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined ms-sm">map</span>Data Bencana
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('berita.index') }}" class="footer-link d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined ms-sm">newspaper</span>Berita Terkini
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('layanan') }}" class="footer-link d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined ms-sm">build</span>Layanan
                        </a>
                    </li>
                    @guest
                    <li class="mb-2">
                        <a href="{{ route('register') }}" class="footer-link d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined ms-sm">person_add</span>Daftar Akun
                        </a>
                    </li>
                    @endguest
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold mb-3">Kontak Darurat</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2 d-flex align-items-center gap-2 opacity-70 small">
                        <span class="material-symbols-outlined msf ms-sm text-danger">call</span>
                        BPBD Kota Bandung: (022) 7234567
                    </li>
                    <li class="mb-2 d-flex align-items-center gap-2 opacity-70 small">
                        <span class="material-symbols-outlined msf ms-sm text-danger">emergency</span>
                        Emergency: 119 / 112
                    </li>
                    <li class="mb-2 d-flex align-items-center gap-2 opacity-70 small">
                        <span class="material-symbols-outlined msf ms-sm text-warning">mail</span>
                        bpbd@bandung.go.id
                    </li>
                </ul>
            </div>
        </div>
        <hr class="mt-4 mb-3" style="border-color:rgba(255,255,255,0.1);">
        <div class="text-center opacity-50 small">
            &copy; {{ date('Y') }} SIPDA Bandung — BPBD Kota Bandung. Sistem versi 2.0 (Laravel)
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    (function() {
        const html    = document.documentElement;
        const toggle  = document.getElementById('themeToggle');
        const icon    = document.getElementById('themeIcon');

        function syncIcon(theme) {
            if (icon) icon.textContent = theme === 'dark' ? 'light_mode' : 'dark_mode';
        }

        syncIcon(html.getAttribute('data-bs-theme'));

        if (toggle) {
            toggle.addEventListener('click', function () {
                const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-bs-theme', next);
                localStorage.setItem('sipda-theme', next);
                syncIcon(next);
            });
        }
    })();
</script>
@stack('scripts')
</body>
</html>
