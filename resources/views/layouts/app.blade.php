<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPDA Bandung') - Sistem Informasi Peringatan Dini Bencana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
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

{{-- Fixed flash toast --}}
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

{{-- ════════════════════════════════════════════════════════════
     FOOTER SIPDA — REDESIGN
════════════════════════════════════════════════════════════ --}}
<style>
.sipda-footer-new {
    background: #040C18;
    border-top: 1px solid rgba(255,255,255,0.06);
    font-family: 'Inter', sans-serif;
    color: #E8EEF6;
}

/* ── Cara Pelaporan Banner ── */
.footer-laporan-banner {
    background: linear-gradient(135deg, #0D1A30 0%, #112040 50%, #0a1628 100%);
    border-bottom: 1px solid rgba(255,255,255,0.06);
    padding: 52px 0;
    position: relative;
    overflow: hidden;
}
.footer-laporan-banner::before {
    content: '';
    position: absolute;
    width: 500px; height: 500px;
    background: rgba(255,78,42,0.05);
    border-radius: 50%;
    filter: blur(80px);
    top: -150px; right: -100px;
    pointer-events: none;
}
.footer-laporan-banner::after {
    content: '';
    position: absolute;
    width: 300px; height: 300px;
    background: rgba(59,130,246,0.05);
    border-radius: 50%;
    filter: blur(60px);
    bottom: -80px; left: 5%;
    pointer-events: none;
}

.laporan-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    z-index: 1;
}
.laporan-step-num {
    width: 52px; height: 52px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    flex-shrink: 0;
}
.laporan-step-num.sn-1 { background: rgba(255,78,42,0.15); border: 1px solid rgba(255,78,42,0.3); }
.laporan-step-num.sn-2 { background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3); }
.laporan-step-num.sn-3 { background: rgba(251,191,36,0.12); border: 1px solid rgba(251,191,36,0.25); }
.laporan-step-num.sn-4 { background: rgba(0,212,170,0.12); border: 1px solid rgba(0,212,170,0.25); }

.laporan-step-icon { font-size: 22px; font-variation-settings: 'FILL' 1; }
.sn-1 .laporan-step-icon { color: #FF4E2A; }
.sn-2 .laporan-step-icon { color: #3B82F6; }
.sn-3 .laporan-step-icon { color: #FBBF24; }
.sn-4 .laporan-step-icon { color: #00D4AA; }

.laporan-step-title {
    font-family: 'Space Grotesk', 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 5px;
}
.laporan-step-desc {
    font-size: 12px;
    color: #6B82A0;
    line-height: 1.55;
    max-width: 160px;
}
.laporan-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    padding-top: 4px;
    color: rgba(255,255,255,0.15);
    flex-shrink: 0;
}

/* ── Fitur Cards ── */
.footer-fitur-section {
    padding: 48px 0;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    background: #06101F;
}
.footer-fitur-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 16px;
    padding: 1.2rem 1.25rem;
    transition: background 0.2s, border-color 0.2s, transform 0.2s;
    height: 100%;
}
.footer-fitur-card:hover {
    background: rgba(255,255,255,0.06);
    border-color: rgba(255,255,255,0.13);
    transform: translateY(-3px);
}
.footer-fitur-icon {
    width: 40px; height: 40px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 11px;
}
.ffi-1 { background: rgba(255,78,42,0.14); }
.ffi-2 { background: rgba(59,130,246,0.14); }
.ffi-3 { background: rgba(251,191,36,0.12); }
.ffi-4 { background: rgba(0,212,170,0.12); }
.ffi-5 { background: rgba(139,92,246,0.14); }
.ffi-6 { background: rgba(20,184,166,0.12); }

.footer-fitur-title {
    font-family: 'Space Grotesk', 'Inter', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 4px;
}
.footer-fitur-desc { font-size: 11.5px; color: #6B82A0; line-height: 1.5; }

/* ── Main Footer Body ── */
.footer-main-body {
    padding: 52px 0 36px;
    background: #040C18;
}
.footer-brand-name {
    font-family: 'Space Grotesk', 'Inter', sans-serif;
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.3px;
}
.footer-brand-desc {
    font-size: 13px;
    color: #6B82A0;
    line-height: 1.65;
    margin-top: 10px;
    max-width: 240px;
}
.footer-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(0,212,170,0.1);
    border: 1px solid rgba(0,212,170,0.2);
    border-radius: 100px;
    padding: 5px 12px;
    font-size: 11.5px;
    font-weight: 600;
    color: #00D4AA;
    margin-top: 14px;
}
.footer-status-dot {
    width: 6px; height: 6px;
    background: #00D4AA;
    border-radius: 50%;
    animation: fsDotBlink 2s ease-in-out infinite;
}
@keyframes fsDotBlink { 0%,100%{opacity:1} 50%{opacity:0.3} }

.footer-col-title {
    font-family: 'Space Grotesk', 'Inter', sans-serif;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #6B82A0;
    margin-bottom: 16px;
}
.footer-nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13.5px;
    color: #94A3B8;
    text-decoration: none;
    padding: 5px 0;
    transition: color 0.15s, gap 0.15s;
}
.footer-nav-link:hover { color: #fff; gap: 10px; }
.footer-nav-link .material-symbols-outlined { font-size: 15px; font-variation-settings: 'FILL' 1; flex-shrink: 0; }

.footer-emergency-card {
    background: rgba(255,78,42,0.07);
    border: 1px solid rgba(255,78,42,0.18);
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 12px;
}
.footer-emergency-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #FF4E2A;
    margin-bottom: 6px;
}
.footer-emergency-number {
    font-family: 'Space Grotesk', 'Inter', sans-serif;
    font-size: 1.5rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.3px;
    line-height: 1;
}
.footer-contact-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    color: #6B82A0;
    padding: 5px 0;
}
.footer-contact-row .material-symbols-outlined { font-size: 14px; font-variation-settings: 'FILL' 1; flex-shrink: 0; }

.footer-bottom-bar {
    border-top: 1px solid rgba(255,255,255,0.06);
    padding: 18px 0;
    background: #040C18;
}
.footer-bottom-text { font-size: 12px; color: #4A5568; }
.footer-bottom-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 100px;
    padding: 3px 10px;
    font-size: 11px;
    color: #6B82A0;
}

@media (max-width: 767px) {
    .laporan-steps-row { flex-direction: column; gap: 0 !important; }
    .laporan-arrow { transform: rotate(90deg); padding: 0; margin: 4px 0; }
    .laporan-step-desc { max-width: 100%; }
}
</style>

<footer class="sipda-footer-new">

    {{-- ══ CARA PELAPORAN ══ --}}
    <div class="footer-laporan-banner">
        <div class="container" style="position:relative;z-index:1;">
            <div class="text-center mb-5">
                <div style="display:inline-flex;align-items:center;gap:7px;font-size:11px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:#FF4E2A;margin-bottom:10px;font-family:'Space Grotesk','Inter',sans-serif;">
                    <span class="material-symbols-outlined" style="font-size:13px;font-variation-settings:'FILL' 1;">campaign</span>
                    Panduan Pelaporan
                </div>
                <h3 style="font-family:'Space Grotesk','Inter',sans-serif;font-size:clamp(1.3rem,2.5vw,1.75rem);font-weight:800;color:#fff;margin-bottom:8px;letter-spacing:-0.3px;">
                    Cara Melaporkan Bencana
                </h3>
                <p style="font-size:14px;color:#6B82A0;max-width:400px;margin:0 auto;line-height:1.65;">
                    Laporan kamu membantu BPBD merespons lebih cepat. Ikuti 4 langkah mudah ini.
                </p>
            </div>

            <div class="d-flex align-items-start justify-content-center flex-wrap gap-2 laporan-steps-row">
                <div class="laporan-step" style="flex:1;min-width:140px;max-width:180px;">
                    <div class="laporan-step-num sn-1">
                        <span class="material-symbols-outlined laporan-step-icon">person_add</span>
                    </div>
                    <div class="laporan-step-title">Daftar Akun</div>
                    <div class="laporan-step-desc">Buat akun gratis di SIPDA Bandung. Hanya butuh email dan nama lengkap.</div>
                </div>
                <div class="laporan-arrow">
                    <span class="material-symbols-outlined" style="font-size:24px;">arrow_forward</span>
                </div>
                <div class="laporan-step" style="flex:1;min-width:140px;max-width:180px;">
                    <div class="laporan-step-num sn-2">
                        <span class="material-symbols-outlined laporan-step-icon">location_on</span>
                    </div>
                    <div class="laporan-step-title">Pilih Lokasi</div>
                    <div class="laporan-step-desc">Tandai titik kejadian di peta atau ketik nama lokasi secara manual.</div>
                </div>
                <div class="laporan-arrow">
                    <span class="material-symbols-outlined" style="font-size:24px;">arrow_forward</span>
                </div>
                <div class="laporan-step" style="flex:1;min-width:140px;max-width:180px;">
                    <div class="laporan-step-num sn-3">
                        <span class="material-symbols-outlined laporan-step-icon">edit_document</span>
                    </div>
                    <div class="laporan-step-title">Isi Detail</div>
                    <div class="laporan-step-desc">Pilih jenis bencana, deskripsikan kejadian, dan unggah foto jika ada.</div>
                </div>
                <div class="laporan-arrow">
                    <span class="material-symbols-outlined" style="font-size:24px;">arrow_forward</span>
                </div>
                <div class="laporan-step" style="flex:1;min-width:140px;max-width:180px;">
                    <div class="laporan-step-num sn-4">
                        <span class="material-symbols-outlined laporan-step-icon">check_circle</span>
                    </div>
                    <div class="laporan-step-title">Kirim & Pantau</div>
                    <div class="laporan-step-desc">Laporan dikirim ke BPBD. Pantau status verifikasi di dashboard kamu.</div>
                </div>
            </div>

            <div class="text-center mt-5">
                @guest
                <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#FF4E2A,#FF7A4D);color:#fff;font-family:'Space Grotesk','Inter',sans-serif;font-weight:700;font-size:14.5px;padding:13px 30px;border-radius:12px;text-decoration:none;box-shadow:0 6px 24px rgba(255,78,42,0.35);transition:transform 0.18s,box-shadow 0.18s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 32px rgba(255,78,42,0.5)'" onmouseout="this.style.transform='';this.style.boxShadow='0 6px 24px rgba(255,78,42,0.35)'">
                    <span class="material-symbols-outlined" style="font-size:18px;font-variation-settings:'FILL' 1;">person_add</span>
                    Mulai Buat Laporan — Gratis
                </a>
                @else
                    @if(auth()->user()->isMasyarakat())
                    <a href="{{ route('user.laporan.create') }}" style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#FF4E2A,#FF7A4D);color:#fff;font-family:'Space Grotesk','Inter',sans-serif;font-weight:700;font-size:14.5px;padding:13px 30px;border-radius:12px;text-decoration:none;box-shadow:0 6px 24px rgba(255,78,42,0.35);transition:transform 0.18s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                        <span class="material-symbols-outlined" style="font-size:18px;font-variation-settings:'FILL' 1;">campaign</span>
                        Buat Laporan Sekarang
                    </a>
                    @endif
                @endguest
            </div>
        </div>
    </div>

    {{-- ══ FITUR-FITUR ══ --}}
    <div class="footer-fitur-section">
        <div class="container">
            <div class="text-center mb-4">
                <div style="font-size:11px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:#8B5CF6;margin-bottom:8px;display:flex;align-items:center;justify-content:center;gap:6px;font-family:'Space Grotesk','Inter',sans-serif;">
                    <span class="material-symbols-outlined" style="font-size:13px;font-variation-settings:'FILL' 1;">auto_awesome</span>
                    Fitur Platform
                </div>
                <h4 style="font-family:'Space Grotesk','Inter',sans-serif;font-size:1.2rem;font-weight:800;color:#fff;margin:0;letter-spacing:-0.2px;">
                    Semua yang Kamu Butuhkan
                </h4>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="footer-fitur-card">
                        <div class="footer-fitur-icon ffi-1">
                            <span class="material-symbols-outlined" style="font-size:20px;color:#FF4E2A;font-variation-settings:'FILL' 1;">sensors</span>
                        </div>
                        <div class="footer-fitur-title">Pantau Real-time</div>
                        <div class="footer-fitur-desc">Data bencana diperbarui langsung 24/7</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="footer-fitur-card">
                        <div class="footer-fitur-icon ffi-2">
                            <span class="material-symbols-outlined" style="font-size:20px;color:#3B82F6;font-variation-settings:'FILL' 1;">map</span>
                        </div>
                        <div class="footer-fitur-title">Peta Interaktif</div>
                        <div class="footer-fitur-desc">Visualisasi bencana di peta Kota Bandung</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="footer-fitur-card">
                        <div class="footer-fitur-icon ffi-3">
                            <span class="material-symbols-outlined" style="font-size:20px;color:#FBBF24;font-variation-settings:'FILL' 1;">campaign</span>
                        </div>
                        <div class="footer-fitur-title">Lapor Bencana</div>
                        <div class="footer-fitur-desc">Kirim laporan langsung ke petugas BPBD</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="footer-fitur-card">
                        <div class="footer-fitur-icon ffi-4">
                            <span class="material-symbols-outlined" style="font-size:20px;color:#00D4AA;font-variation-settings:'FILL' 1;">partly_cloudy_day</span>
                        </div>
                        <div class="footer-fitur-title">Info Cuaca</div>
                        <div class="footer-fitur-desc">Cuaca 6 kota Jawa Barat tiap 30 menit</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="footer-fitur-card">
                        <div class="footer-fitur-icon ffi-5">
                            <span class="material-symbols-outlined" style="font-size:20px;color:#A78BFA;font-variation-settings:'FILL' 1;">newspaper</span>
                        </div>
                        <div class="footer-fitur-title">Berita Resmi</div>
                        <div class="footer-fitur-desc">Informasi resmi langsung dari BPBD</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="footer-fitur-card">
                        <div class="footer-fitur-icon ffi-6">
                            <span class="material-symbols-outlined" style="font-size:20px;color:#2DD4BF;font-variation-settings:'FILL' 1;">manage_accounts</span>
                        </div>
                        <div class="footer-fitur-title">Dashboard</div>
                        <div class="footer-fitur-desc">Pantau status laporanmu kapan saja</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ MAIN FOOTER BODY ══ --}}
    <div class="footer-main-body">
        <div class="container">
            <div class="row g-5">

                {{-- Brand --}}
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#FF4E2A,#FF7A4D);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span class="material-symbols-outlined" style="font-size:20px;color:#fff;font-variation-settings:'FILL' 1;">security</span>
                        </div>
                        <span class="footer-brand-name">SIPDA Bandung</span>
                    </div>
                    <p class="footer-brand-desc">
                        Sistem Informasi Peringatan Dini Bencana Alam Kota Bandung — terintegrasi dengan BPBD Kota Bandung.
                    </p>
                    <div class="footer-status-badge">
                        <span class="footer-status-dot"></span>
                        Sistem Aktif 24/7
                    </div>
                </div>

                {{-- Navigasi --}}
                <div class="col-6 col-lg-2">
                    <div class="footer-col-title">Navigasi</div>
                    <a href="{{ route('home') }}" class="footer-nav-link">
                        <span class="material-symbols-outlined">home</span>Beranda
                    </a>
                    <a href="{{ route('bencana.index') }}" class="footer-nav-link">
                        <span class="material-symbols-outlined">warning</span>Data Bencana
                    </a>
                    <a href="{{ route('berita.index') }}" class="footer-nav-link">
                        <span class="material-symbols-outlined">newspaper</span>Berita
                    </a>
                    <a href="{{ route('layanan') }}" class="footer-nav-link">
                        <span class="material-symbols-outlined">grid_view</span>Layanan
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="footer-nav-link" style="color:#FF4E2A;">
                        <span class="material-symbols-outlined">person_add</span>Daftar Gratis
                    </a>
                    @endguest
                </div>

                {{-- Akun --}}
                <div class="col-6 col-lg-2">
                    <div class="footer-col-title">Akun</div>
                    @guest
                    <a href="{{ route('login') }}" class="footer-nav-link">
                        <span class="material-symbols-outlined">login</span>Masuk
                    </a>
                    <a href="{{ route('register') }}" class="footer-nav-link">
                        <span class="material-symbols-outlined">person_add</span>Daftar
                    </a>
                    @else
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="footer-nav-link">
                            <span class="material-symbols-outlined">dashboard</span>Dashboard Admin
                        </a>
                        @elseif(auth()->user()->isPetugas())
                        <a href="{{ route('petugas.dashboard') }}" class="footer-nav-link">
                            <span class="material-symbols-outlined">dashboard</span>Dashboard Petugas
                        </a>
                        @else
                        <a href="{{ route('user.dashboard') }}" class="footer-nav-link">
                            <span class="material-symbols-outlined">dashboard</span>Dashboard
                        </a>
                        <a href="{{ route('user.laporan.create') }}" class="footer-nav-link" style="color:#00D4AA;">
                            <span class="material-symbols-outlined">add_circle</span>Buat Laporan
                        </a>
                        @endif
                        <a href="{{ route('user.profil') }}" class="footer-nav-link">
                            <span class="material-symbols-outlined">manage_accounts</span>Profil
                        </a>
                    @endguest
                </div>

                {{-- Kontak Darurat --}}
                <div class="col-md-6 col-lg-5">
                    <div class="footer-col-title">Kontak Darurat</div>
                    <div class="footer-emergency-card">
                        <div class="footer-emergency-label">🚨 Nomor Darurat Nasional</div>
                        <div class="d-flex gap-4 mt-1 flex-wrap">
                            <div>
                                <div style="font-size:10px;color:#6B82A0;margin-bottom:3px;">Ambulans / Kebakaran</div>
                                <div class="footer-emergency-number">119</div>
                            </div>
                            <div style="width:1px;background:rgba(255,78,42,0.2);flex-shrink:0;"></div>
                            <div>
                                <div style="font-size:10px;color:#6B82A0;margin-bottom:3px;">Darurat Umum</div>
                                <div class="footer-emergency-number">112</div>
                            </div>
                        </div>
                    </div>
                    <div class="footer-contact-row">
                        <span class="material-symbols-outlined" style="color:#FF4E2A;">call</span>
                        <span>BPBD Kota Bandung: <strong style="color:#E8EEF6;">(022) 7234567</strong></span>
                    </div>
                    <div class="footer-contact-row">
                        <span class="material-symbols-outlined" style="color:#FBBF24;">mail</span>
                        <span>bpbd@bandung.go.id</span>
                    </div>
                    <div class="footer-contact-row">
                        <span class="material-symbols-outlined" style="color:#3B82F6;">location_on</span>
                        <span>Jl. Sukabumi No.17, Bandung</span>
                    </div>
                    <div class="footer-contact-row">
                        <span class="material-symbols-outlined" style="color:#00D4AA;">schedule</span>
                        <span>Posko buka 24 jam setiap hari</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ BOTTOM BAR ══ --}}
    <div class="footer-bottom-bar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <span class="footer-bottom-text">
                    &copy; {{ date('Y') }} SIPDA Bandung — BPBD Kota Bandung. Hak cipta dilindungi.
                </span>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="footer-bottom-badge">
                        <span class="material-symbols-outlined" style="font-size:12px;font-variation-settings:'FILL' 1;">code</span>
                        Laravel v{{ app()->version() }}
                    </span>
                    <span class="footer-bottom-badge">
                        <span class="material-symbols-outlined" style="font-size:12px;font-variation-settings:'FILL' 1;">verified_user</span>
                        Sistem v2.0
                    </span>
                </div>
            </div>
        </div>
    </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    (function() {
        const html   = document.documentElement;
        const toggle = document.getElementById('themeToggle');
        const icon   = document.getElementById('themeIcon');

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