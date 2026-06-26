@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
<style>
/* ── Hero decorative glows ──────────────────────────────── */
.hero-glow {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    pointer-events: none;
    animation: glowDrift 9s ease-in-out infinite alternate;
}
@keyframes glowDrift {
    0%   { transform: translate(0, 0) scale(1); }
    100% { transform: translate(28px, -18px) scale(1.1); }
}

/* Hero stat box */
.hero-stat-box {
    border-radius: 16px;
    padding: 1rem 1.15rem;
    transition: transform 0.22s, box-shadow 0.22s;
}
.hero-stat-box:hover { transform: translateY(-4px); }

/* Scroll-reveal */
.reveal {
    opacity: 0;
    transform: translateY(26px);
    transition: opacity 0.55s ease, transform 0.55s ease;
}
.reveal.visible { opacity: 1; transform: translateY(0); }

/* Bencana cards */
.bencana-card {
    border-left: 4px solid transparent !important;
    transition: transform 0.2s, box-shadow 0.2s;
}
.bencana-card:hover { transform: translateY(-4px); }
.bc-darurat { border-left-color: #ef4444 !important; }
.bc-siaga   { border-left-color: #3b82f6 !important; }
.bc-waspada { border-left-color: #f59e0b !important; }

/* Status icon circle */
.status-icon {
    width: 40px; height: 40px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Berita cards */
.berita-featured { transition: transform 0.2s, box-shadow 0.2s; }
.berita-featured:hover { transform: translateY(-4px); }
.berita-sm { transition: transform 0.2s; }
.berita-sm:hover { transform: translateX(5px); }

/* Feature cards */
.feature-card {
    border-radius: 20px;
    padding: 1.75rem;
    transition: transform 0.22s, box-shadow 0.22s;
}
.feature-card:hover { transform: translateY(-6px); }
.feature-icon {
    width: 56px; height: 56px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.1rem;
}

/* CTA benefit items */
.cta-benefit {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.9rem;
    opacity: 0.88;
}
.cta-benefit-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    background: rgba(255,255,255,0.16);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Scroll indicator */
.scroll-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    opacity: 0.45;
    animation: scrollBounce 2.2s ease-in-out infinite;
}
@keyframes scrollBounce {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(7px); }
}

/* Alert ticker */
.ticker-track {
    overflow: hidden;
    flex: 1;
    -webkit-mask-image: linear-gradient(90deg, transparent, black 5%, black 95%, transparent);
    mask-image: linear-gradient(90deg, transparent, black 5%, black 95%, transparent);
}
.ticker-inner {
    display: flex;
    gap: 3rem;
    white-space: nowrap;
    animation: tickerScroll 18s linear infinite;
    width: max-content;
}
@keyframes tickerScroll {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* Mini map legend */
.home-map-legend {
    background: #fff;
    border-radius: 10px;
    padding: 9px 12px;
    box-shadow: 0 3px 14px rgba(0,0,0,0.1);
    font-family: 'Inter', sans-serif;
    min-width: 120px;
    border: 1px solid rgba(0,0,0,0.06);
    font-size: 11px;
}
[data-bs-theme="dark"] .home-map-legend {
    background: #1e293b;
    border-color: rgba(255,255,255,0.07);
    color: #e2e8f0;
}
.home-map-legend-dot {
    width: 9px; height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.18);
}
</style>
@endpush

@section('content')

{{-- ── Emergency Alert Ticker (Darurat only) ────────────── --}}
@php $darurat = $bencanaAktif->where('tingkat_status', 'Darurat'); @endphp
@if($darurat->isNotEmpty())
<div style="background: linear-gradient(90deg,#9f1239,#dc2626); overflow:hidden; position:relative; z-index:10;">
    <div class="container-fluid py-2">
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-white text-danger fw-black px-2 py-1 flex-shrink-0 d-flex align-items-center gap-1" style="font-size:10px;letter-spacing:.3px;">
                <span class="material-symbols-outlined msf" style="font-size:11px;">warning</span>DARURAT
            </span>
            <div class="ticker-track">
                <div class="ticker-inner text-white" style="font-size:0.82rem;font-weight:500;">
                    @foreach($darurat as $d)
                    <span class="d-inline-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf" style="font-size:13px;">location_on</span>
                        {{ $d->jenis?->nama_bencana ?? 'Bencana' }} — {{ $d->lokasi }}
                    </span>
                    @endforeach
                    {{-- duplicate for seamless loop --}}
                    @foreach($darurat as $d)
                    <span class="d-inline-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf" style="font-size:13px;">location_on</span>
                        {{ $d->jenis?->nama_bencana ?? 'Bencana' }} — {{ $d->lokasi }}
                    </span>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('bencana.index') }}" class="btn btn-sm btn-outline-light flex-shrink-0 d-flex align-items-center gap-1" style="font-size:11px;">
                Lihat <span class="material-symbols-outlined ms-sm">arrow_forward</span>
            </a>
        </div>
    </div>
</div>
@endif

{{-- ── HERO ──────────────────────────────────────────────── --}}
<div class="hero-section position-relative overflow-hidden" style="min-height:540px;">
    <div class="hero-glow" style="width:560px;height:560px;background:rgba(99,102,241,0.22);top:-140px;right:-80px;"></div>
    <div class="hero-glow" style="width:320px;height:320px;background:rgba(245,158,11,0.12);bottom:-40px;left:8%;animation-delay:-5s;"></div>
    <div class="hero-glow" style="width:200px;height:200px;background:rgba(239,68,68,0.1);top:30%;left:40%;animation-delay:-2s;"></div>

    <div class="container py-5">
        <div class="row align-items-center g-5">
            {{-- Left: Text --}}
            <div class="col-lg-6">
                <div class="d-inline-flex align-items-center gap-2 badge bg-warning text-dark px-3 py-2 rounded-pill mb-4" style="font-size:0.78rem;">
                    <span class="material-symbols-outlined msf text-danger" style="font-size:11px;animation:glowDrift 1.5s ease-in-out infinite alternate;">radio_button_checked</span>
                    Sistem Aktif 24/7
                </div>
                <h1 class="display-4 fw-black mb-3 lh-sm">
                    Sistem Informasi<br>
                    <span style="background:linear-gradient(135deg,#ffd700,#ff6b35);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                        Peringatan Dini<br>Bencana
                    </span>
                </h1>
                <p class="fs-5 text-white-50 mb-4 lh-base" style="max-width:480px;">
                    Pantau, laporkan, dan dapatkan informasi bencana alam Kota Bandung secara real-time. Bersama kita jaga keselamatan warga.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="{{ route('bencana.index') }}" class="btn btn-warning btn-lg fw-bold px-4 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf">crisis_alert</span>Pantau Bencana
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined">person_add</span>Daftar &amp; Lapor
                    </a>
                    @else
                        @if(auth()->user()->isMasyarakat())
                        <a href="{{ route('user.laporan.create') }}" class="btn btn-outline-light btn-lg px-4 d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined">campaign</span>Buat Laporan
                        </a>
                        @endif
                    @endguest
                </div>
                <div class="scroll-indicator d-none d-lg-flex">
                    <small class="text-white-50" style="letter-spacing:1.5px;text-transform:uppercase;font-size:10px;">Scroll</small>
                    <span class="material-symbols-outlined text-white-50 ms-sm">keyboard_arrow_down</span>
                </div>
            </div>

            {{-- Right: Stat boxes with animated counters --}}
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="hero-stat-box" style="background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="status-icon" style="background:rgba(239,68,68,.2);">
                                    <span class="material-symbols-outlined msf text-danger ms-sm">crisis_alert</span>
                                </div>
                                <small class="text-white-50">Darurat</small>
                            </div>
                            <div class="display-5 fw-black text-danger stat-counter" data-target="{{ $statistik['bencana_darurat'] }}">0</div>
                            <div class="text-white-50" style="font-size:11px;margin-top:2px;">Bencana Darurat</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-stat-box" style="background:rgba(59,130,246,.15);border:1px solid rgba(59,130,246,.3);">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="status-icon" style="background:rgba(59,130,246,.2);">
                                    <span class="material-symbols-outlined msf text-primary ms-sm">database</span>
                                </div>
                                <small class="text-white-50">Total</small>
                            </div>
                            <div class="display-5 fw-black text-primary stat-counter" data-target="{{ $statistik['total_bencana'] }}">0</div>
                            <div class="text-white-50" style="font-size:11px;margin-top:2px;">Total Bencana</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-stat-box" style="background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.3);">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="status-icon" style="background:rgba(16,185,129,.2);">
                                    <span class="material-symbols-outlined msf text-success ms-sm">campaign</span>
                                </div>
                                <small class="text-white-50">Laporan</small>
                            </div>
                            <div class="display-5 fw-black text-success stat-counter" data-target="{{ $statistik['total_laporan'] }}">0</div>
                            <div class="text-white-50" style="font-size:11px;margin-top:2px;">Total Laporan</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="hero-stat-box" style="background:rgba(245,158,11,.15);border:1px solid rgba(245,158,11,.3);">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="status-icon" style="background:rgba(245,158,11,.2);">
                                    <span class="material-symbols-outlined msf text-warning ms-sm">pending_actions</span>
                                </div>
                                <small class="text-white-50">Pending</small>
                            </div>
                            <div class="display-5 fw-black text-warning stat-counter" data-target="{{ $statistik['laporan_pending'] }}">0</div>
                            <div class="text-white-50" style="font-size:11px;margin-top:2px;">Laporan Pending</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── BENCANA AKTIF + MINI MAP ──────────────────────────── --}}
<section class="py-5 section-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 reveal">
            <div>
                <h2 class="fw-bold mb-1 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined msf text-danger ms-lg">warning</span>Bencana Aktif
                </h2>
                <p class="text-muted mb-0 small">
                    @if($bencanaAktif->isNotEmpty())
                        {{ $bencanaAktif->count() }} kejadian perlu diwaspadai di Kota Bandung
                    @else
                        Tidak ada kejadian bencana aktif saat ini
                    @endif
                </p>
            </div>
            <a href="{{ route('bencana.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-1">
                Lihat Semua <span class="material-symbols-outlined ms-sm">arrow_forward</span>
            </a>
        </div>

        @if($bencanaAktif->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 reveal">
            <div class="card-body py-5 text-center">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
                     style="width:80px;height:80px;background:rgba(16,185,129,0.1);">
                    <span class="material-symbols-outlined msf text-success ms-xxl">verified_user</span>
                </div>
                <h5 class="fw-bold mb-1">Kondisi Aman</h5>
                <p class="text-muted mb-0">Tidak ada bencana aktif saat ini di Kota Bandung.</p>
            </div>
        </div>
        @else
        <div class="row g-4">
            {{-- Cards --}}
            <div class="col-lg-7">
                <div class="row g-3">
                    @foreach($bencanaAktif->take(4) as $b)
                    @php
                        $statusKey = strtolower($b->tingkat_status);
                        $iconMap   = ['darurat'=>'crisis_alert','siaga'=>'warning','waspada'=>'error_outline'];
                        $rgbaMap   = ['darurat'=>'239,68,68','siaga'=>'59,130,246','waspada'=>'245,158,11'];
                    @endphp
                    <div class="col-md-6 reveal" style="transition-delay:{{ $loop->index * 0.07 }}s">
                        <div class="card h-100 border-0 shadow-sm rounded-4 bencana-card bc-{{ $statusKey }}">
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div class="status-icon" style="background:rgba({{ $rgbaMap[$statusKey] ?? '107,114,128' }},.12);">
                                        <span class="material-symbols-outlined msf text-{{ $b->status_color }} ms-sm">
                                            {{ $iconMap[$statusKey] ?? 'info' }}
                                        </span>
                                    </div>
                                    <div style="min-width:0;">
                                        <div class="fw-bold text-truncate">{{ $b->jenis?->nama_bencana ?? 'Bencana' }}</div>
                                        <span class="badge bg-{{ $b->status_color }} rounded-pill" style="font-size:10px;">{{ $b->tingkat_status }}</span>
                                    </div>
                                </div>
                                <p class="text-muted small d-flex align-items-start gap-1 mb-2">
                                    <span class="material-symbols-outlined msf text-danger ms-sm flex-shrink-0" style="margin-top:1px;">location_on</span>
                                    {{ Str::limit($b->lokasi, 52) }}
                                </p>
                                <p class="text-muted small mb-3">{{ Str::limit($b->deskripsi, 72) }}</p>
                                <a href="{{ route('bencana.show', $b) }}"
                                   class="btn btn-sm btn-outline-{{ $b->status_color }} w-100 d-flex align-items-center justify-content-center gap-1">
                                    <span class="material-symbols-outlined ms-sm">arrow_forward_ios</span>Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Mini Map --}}
            <div class="col-lg-5 reveal" style="transition-delay:0.1s">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                            <span class="material-symbols-outlined msf ms-sm text-primary">map</span>Peta Bencana Aktif
                        </h6>
                        <a href="{{ route('bencana.index') }}" class="text-muted small d-flex align-items-center gap-1" style="text-decoration:none;">
                            Peta lengkap <span class="material-symbols-outlined ms-sm">open_in_new</span>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div id="homeMap" style="height:340px;border-radius:0 0 1rem 1rem;"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

{{-- ── BERITA TERKINI ────────────────────────────────────── --}}
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 reveal">
            <div>
                <h2 class="fw-bold mb-1 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined msf text-primary ms-lg">newspaper</span>Berita Terkini
                </h2>
                <p class="text-muted mb-0 small">Informasi resmi dari BPBD Kota Bandung</p>
            </div>
            <a href="{{ route('berita.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-1">
                Lihat Semua <span class="material-symbols-outlined ms-sm">arrow_forward</span>
            </a>
        </div>

        @if($beritaTerbaru->isEmpty())
        <div class="text-center py-5 text-muted reveal">
            <span class="material-symbols-outlined ms-xxl d-block mb-3 opacity-30">inbox</span>
            Belum ada berita tersedia.
        </div>
        @else
        @php $featured = $beritaTerbaru->first(); $others = $beritaTerbaru->skip(1)->take(2); @endphp
        <div class="row g-4">
            {{-- Featured article --}}
            <div class="col-lg-7 reveal">
                <div class="card h-100 border-0 shadow-sm rounded-4 berita-featured">
                    @if($featured->foto)
                        <img src="{{ Storage::url($featured->foto) }}" class="card-img-top rounded-top-4"
                             style="height:260px;object-fit:cover;" alt="{{ $featured->judul }}">
                    @else
                        <div class="rounded-top-4 d-flex align-items-center justify-content-center berita-placeholder"
                             style="height:260px;background:linear-gradient(135deg,#4338ca,#7c3aed);">
                            <span class="material-symbols-outlined text-white opacity-30" style="font-size:72px;">newspaper</span>
                        </div>
                    @endif
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-primary rounded-pill px-2">Terbaru</span>
                            <small class="text-muted d-flex align-items-center gap-1">
                                <span class="material-symbols-outlined ms-sm">schedule</span>{{ $featured->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <h4 class="fw-bold mb-2 lh-sm">{{ Str::limit($featured->judul, 80) }}</h4>
                        <p class="text-muted mb-4">{{ Str::limit(strip_tags($featured->isi), 135) }}</p>
                        <a href="{{ route('berita.show', $featured) }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <span class="material-symbols-outlined msf ms-sm">article</span>Baca Selengkapnya
                        </a>
                    </div>
                </div>
            </div>

            {{-- Smaller articles --}}
            <div class="col-lg-5 d-flex flex-column gap-4">
                @foreach($others as $berita)
                <div class="reveal" style="transition-delay:{{ $loop->index * 0.1 + 0.12 }}s">
                    <div class="card border-0 shadow-sm rounded-4 berita-sm">
                        <div class="row g-0">
                            <div class="col-4">
                                @if($berita->foto)
                                    <img src="{{ Storage::url($berita->foto) }}" class="img-fluid rounded-start-4"
                                         style="height:132px;object-fit:cover;width:100%;" alt="{{ $berita->judul }}">
                                @else
                                    <div class="rounded-start-4 d-flex align-items-center justify-content-center berita-placeholder"
                                         style="min-height:132px;background:linear-gradient(135deg,#0d1f3c,#1a3a6b);">
                                        <span class="material-symbols-outlined text-white opacity-30" style="font-size:28px;">newspaper</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-8">
                                <div class="card-body py-3 px-3">
                                    <small class="text-muted d-flex align-items-center gap-1 mb-1">
                                        <span class="material-symbols-outlined ms-sm">schedule</span>{{ $berita->created_at->diffForHumans() }}
                                    </small>
                                    <h6 class="fw-bold mb-3 lh-sm" style="font-size:0.875rem;">{{ Str::limit($berita->judul, 65) }}</h6>
                                    <a href="{{ route('berita.show', $berita) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                        Baca <span class="material-symbols-outlined ms-sm">arrow_forward_ios</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- More berita indicator if there are more --}}
                @if($beritaTerbaru->count() > 3)
                <div class="reveal" style="transition-delay:0.28s">
                    <a href="{{ route('berita.index') }}" class="card border-0 shadow-sm rounded-4 text-decoration-none d-flex align-items-center justify-content-center py-4"
                       style="border:2px dashed var(--bs-border-color) !important;background:transparent;">
                        <span class="material-symbols-outlined text-muted ms-lg">add_circle</span>
                        <span class="text-muted small mt-1">Lihat {{ $beritaTerbaru->count() - 3 }} berita lainnya</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</section>

{{-- ── FITUR UTAMA ───────────────────────────────────────── --}}
<section class="py-5 section-light">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="badge bg-primary rounded-pill px-3 py-2 mb-3">Platform SIPDA</span>
            <h2 class="fw-bold mb-2">Fitur Utama Sistem</h2>
            <p class="text-muted" style="max-width:480px;margin:0 auto;">Teknologi modern untuk keselamatan dan kesiapsiagaan warga Kota Bandung</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4 reveal" style="transition-delay:0.05s">
                <div class="feature-card h-100 shadow-sm" style="background:linear-gradient(135deg,rgba(99,102,241,.06),rgba(139,92,246,.04));border:1px solid rgba(99,102,241,.12);">
                    <div class="feature-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <span class="material-symbols-outlined msf text-white ms-md">sensors</span>
                    </div>
                    <h5 class="fw-bold mb-2">Pemantauan Real-time</h5>
                    <p class="text-muted small mb-0">Data bencana terkini diperbarui langsung oleh petugas BPBD. Status tersedia 24 jam sehari, 7 hari seminggu tanpa henti.</p>
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay:0.1s">
                <div class="feature-card h-100 shadow-sm" style="background:linear-gradient(135deg,rgba(14,165,233,.06),rgba(6,182,212,.04));border:1px solid rgba(14,165,233,.12);">
                    <div class="feature-icon" style="background:linear-gradient(135deg,#0ea5e9,#06b6d4);">
                        <span class="material-symbols-outlined msf text-white ms-md">campaign</span>
                    </div>
                    <h5 class="fw-bold mb-2">Laporan Masyarakat</h5>
                    <p class="text-muted small mb-0">Warga dapat melaporkan kejadian bencana langsung dari aplikasi. Setiap laporan diverifikasi petugas berpengalaman BPBD.</p>
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay:0.15s">
                <div class="feature-card h-100 shadow-sm" style="background:linear-gradient(135deg,rgba(16,185,129,.06),rgba(5,150,105,.04));border:1px solid rgba(16,185,129,.12);">
                    <div class="feature-icon" style="background:linear-gradient(135deg,#10b981,#059669);">
                        <span class="material-symbols-outlined msf text-white ms-md">map</span>
                    </div>
                    <h5 class="fw-bold mb-2">Peta Interaktif</h5>
                    <p class="text-muted small mb-0">Visualisasi sebaran bencana di peta Kota Bandung secara interaktif dengan keterangan status, lokasi, dan informasi terkini.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA (guests only) ────────────────────────────────── --}}
@guest
<section class="py-5 cta-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-white reveal">
                <span class="badge bg-white text-primary fw-bold mb-3 px-3 py-2">Gratis Selamanya</span>
                <h2 class="fw-black mb-3 lh-sm">Ikut Menjaga Keselamatan<br>Kota Bandung</h2>
                <p class="fs-5 mb-4 opacity-75">Daftarkan diri dan bantu petugas BPBD merespons bencana lebih cepat dengan laporan langsung dari lapangan.</p>
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="cta-benefit">
                        <div class="cta-benefit-icon"><span class="material-symbols-outlined msf ms-sm">check_circle</span></div>
                        Kirim laporan bencana langsung ke BPBD
                    </div>
                    <div class="cta-benefit">
                        <div class="cta-benefit-icon"><span class="material-symbols-outlined msf ms-sm">check_circle</span></div>
                        Pantau status penanganan laporan Anda
                    </div>
                    <div class="cta-benefit">
                        <div class="cta-benefit-icon"><span class="material-symbols-outlined msf ms-sm">check_circle</span></div>
                        Akses peta bencana aktif Kota Bandung
                    </div>
                </div>
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold px-5 d-inline-flex align-items-center gap-2">
                    <span class="material-symbols-outlined msf">person_add</span>Daftar Gratis Sekarang
                </a>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center reveal" style="transition-delay:0.18s">
                <span class="material-symbols-outlined msf text-white" style="font-size:180px;opacity:0.12;">security</span>
            </div>
        </div>
    </div>
</section>
@endguest

@endsection

@push('scripts')
<script>
(function () {
    // ── Animated counter ───────────────────────────────────────
    function animateCounter(el) {
        const target   = parseInt(el.dataset.target) || 0;
        const duration = 1400;
        const start    = performance.now();
        (function step(now) {
            const p    = Math.min((now - start) / duration, 1);
            const ease = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(ease * target);
            if (p < 1) requestAnimationFrame(step);
        })(start);
    }

    // Counters fire on page load (in hero viewport)
    document.querySelectorAll('.stat-counter').forEach(animateCounter);

    // ── Intersection Observer — scroll reveals ─────────────────
    const io = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            e.target.classList.add('visible');
            io.unobserve(e.target);
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => io.observe(el));

    // ── Mini map ───────────────────────────────────────────────
    const mapEl = document.getElementById('homeMap');
    if (!mapEl) return;

    const STATUS_COLORS = { Darurat:'#ef4444', Siaga:'#3b82f6', Waspada:'#f59e0b', Aman:'#10b981' };

    const lightTiles = L.tileLayer(
        'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
        { attribution:'&copy; OSM &copy; CARTO', maxZoom:19 }
    );
    const darkTiles = L.tileLayer(
        'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
        { attribution:'&copy; OSM &copy; CARTO', maxZoom:19 }
    );

    const homeMap = L.map('homeMap', { zoomControl:false, scrollWheelZoom:false })
                     .setView([-6.9175, 107.6191], 12);

    let activeTiles = document.documentElement.getAttribute('data-bs-theme') === 'dark'
        ? darkTiles : lightTiles;
    activeTiles.addTo(homeMap);

    document.getElementById('themeToggle')?.addEventListener('click', () => {
        setTimeout(() => {
            const t = document.documentElement.getAttribute('data-bs-theme');
            homeMap.removeLayer(activeTiles);
            activeTiles = t === 'dark' ? darkTiles : lightTiles;
            activeTiles.addTo(homeMap);
        }, 50);
    });

    // Render markers from bencanaAktif (already contains lat/lng)
    const bencanaData = @json($bencanaAktif);
    const markerList  = [];

    bencanaData.forEach(b => {
        if (!b.latitude || !b.longitude) return;

        const color      = STATUS_COLORS[b.tingkat_status] || '#6b7280';
        const jenis      = b.jenis?.nama_bencana ?? 'Bencana';
        const teksWarna  = b.tingkat_status === 'Waspada' ? '#1a1a1a' : '#fff';
        const isPulse    = b.tingkat_status === 'Darurat';

        const icon = L.divIcon({
            className: '',
            html: `<div class="map-pin${isPulse ? ' map-pin-pulse' : ''}" style="--pin-color:${color}">
                       ${isPulse ? '<div class="map-pin-ring"></div>' : ''}
                       <div class="map-pin-body"></div>
                   </div>`,
            iconSize:[32,42], iconAnchor:[16,42], popupAnchor:[0,-46], tooltipAnchor:[0,-46]
        });

        const marker = L.marker([b.latitude, b.longitude], { icon }).addTo(homeMap);

        marker.bindTooltip(`
            <div style="font-family:'Inter',sans-serif;min-width:180px;line-height:1.4;">
                <div style="font-weight:700;font-size:13px;margin-bottom:4px;">${jenis}</div>
                <div style="font-size:12px;color:#64748b;margin-bottom:6px;">📍 ${b.lokasi}</div>
                <span style="background:${color};color:${teksWarna};border-radius:20px;padding:2px 10px;font-size:11px;font-weight:600;">${b.tingkat_status}</span>
            </div>
        `, { direction:'top', opacity:1, className:'sipda-tooltip' });

        marker.bindPopup(`
            <div style="font-family:'Inter',sans-serif;min-width:200px;line-height:1.4;padding:2px 0;">
                <div style="font-weight:700;font-size:14px;margin-bottom:5px;">${jenis}</div>
                <div style="font-size:12px;color:#64748b;margin-bottom:8px;">📍 ${b.lokasi}</div>
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="background:${color};color:${teksWarna};border-radius:20px;padding:2px 10px;font-size:11px;font-weight:600;">${b.tingkat_status}</span>
                    <a href="/bencana/${b.id}" style="color:#3b82f6;font-size:12px;font-weight:700;text-decoration:none;">Detail &rsaquo;</a>
                </div>
            </div>
        `, { maxWidth:240 });

        markerList.push(marker);
    });

    // Add legend
    const legend = L.control({ position: 'bottomright' });
    legend.onAdd = function () {
        const div = L.DomUtil.create('div', 'home-map-legend');
        div.innerHTML = Object.entries(STATUS_COLORS).map(([s, c]) => `
            <div style="display:flex;align-items:center;gap:7px;margin-bottom:4px;font-weight:500;">
                <span class="home-map-legend-dot" style="background:${c};"></span>${s}
            </div>
        `).join('');
        return div;
    };
    legend.addTo(homeMap);

    if (markerList.length > 0) {
        homeMap.fitBounds(L.featureGroup(markerList).getBounds().pad(0.25));
    }
})();
</script>
@endpush
