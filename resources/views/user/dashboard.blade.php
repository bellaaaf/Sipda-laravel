@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
<div class="container py-4">
    <div class="row g-4">

        {{-- Welcome --}}
        <div class="col-12">
            <div class="welcome-card welcome-card-user d-flex align-items-center gap-3 flex-wrap">
                <div class="rounded-circle p-3" style="background: rgba(255,255,255,.2);">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" class="rounded-circle" width="48" height="48" alt="Avatar">
                    @else
                        <span class="material-symbols-outlined msf ms-xl">account_circle</span>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <h5 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->full_name }}!</h5>
                    <p class="mb-0 opacity-75 small">Pantau laporan dan informasi bencana Anda di sini</p>
                </div>
                <a href="{{ route('user.laporan.create') }}" class="btn btn-warning fw-bold d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined msf ms-sm">campaign</span>Buat Laporan
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3">
                <div class="fs-2 fw-black text-primary">{{ $statistikLaporan['total'] }}</div>
                <div class="text-muted small">Total Laporan</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3">
                <div class="fs-2 fw-black text-warning">{{ $statistikLaporan['pending'] }}</div>
                <div class="text-muted small">Pending</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3">
                <div class="fs-2 fw-black text-info">{{ $statistikLaporan['diproses'] }}</div>
                <div class="text-muted small">Diproses</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3">
                <div class="fs-2 fw-black text-success">{{ $statistikLaporan['selesai'] }}</div>
                <div class="text-muted small">Selesai</div>
            </div>
        </div>

        {{-- Laporan Saya --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header border-0 d-flex justify-content-between align-items-center py-3 px-4">
                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined ms-sm text-primary">chat</span>Laporan Terbaru Saya
                    </h6>
                    <a href="{{ route('user.laporan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @forelse($laporanSaya as $l)
                    <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">{{ $l->jenis_bencana }}</div>
                            <div class="text-muted d-flex align-items-center gap-1" style="font-size:12px;">
                                <span class="material-symbols-outlined msf" style="font-size:13px;">location_on</span>
                                {{ Str::limit($l->lokasi_kejadian, 40) }}
                            </div>
                            <div class="text-muted" style="font-size:11px;">{{ $l->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1">
                            {!! $l->status_badge !!}
                            <a href="{{ route('user.laporan.show', $l) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;">Detail</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <span class="material-symbols-outlined ms-xxl d-block mb-2 opacity-30">inbox</span>
                        Belum ada laporan.
                        <a href="{{ route('user.laporan.create') }}" class="d-block mt-2">Buat laporan sekarang</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-md-4">
            {{-- Notifikasi --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header border-0 py-3 px-4">
                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf ms-sm text-warning">notifications</span>Notifikasi
                    </h6>
                </div>
                <div class="card-body p-0">
                    @forelse($notifikasi as $n)
                    <div class="px-4 py-3 border-bottom">
                        <div class="fw-semibold" style="font-size:13px;">{{ $n->judul }}</div>
                        <div class="text-muted" style="font-size:12px;">{{ Str::limit($n->pesan, 60) }}</div>
                    </div>
                    @empty
                    <div class="text-center py-3 text-muted small px-3">Tidak ada notifikasi baru.</div>
                    @endforelse
                </div>
            </div>

            {{-- Bencana Darurat --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header border-0 py-3 px-4">
                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf ms-sm text-danger">warning</span>Bencana Darurat
                    </h6>
                </div>
                <div class="card-body p-0">
                    @forelse($bencanaAktif as $b)
                    <div class="px-4 py-3 border-bottom">
                        <span class="badge bg-{{ $b->status_color }} me-1">{{ $b->tingkat_status }}</span>
                        <span style="font-size:13px;">{{ $b->jenis?->nama_bencana }}</span>
                        <div class="text-muted d-flex align-items-center gap-1" style="font-size:11px;">
                            <span class="material-symbols-outlined msf" style="font-size:12px;">location_on</span>
                            {{ Str::limit($b->lokasi, 35) }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-3 text-success small d-flex align-items-center justify-content-center gap-1 px-3">
                        <span class="material-symbols-outlined msf ms-sm">verified_user</span>Tidak ada bencana darurat.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
