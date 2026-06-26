@extends('layouts.petugas')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <span class="material-symbols-outlined text-primary">dashboard</span>Dashboard Petugas
    </h3>
    <div class="text-muted small d-flex align-items-center gap-1">
        <span class="material-symbols-outlined ms-sm">calendar_month</span>
        {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

<div class="welcome-card welcome-card-petugas mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle p-3" style="background: rgba(255,255,255,.2);">
            <span class="material-symbols-outlined msf ms-xl">badge</span>
        </div>
        <div>
            <h5 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->full_name }}!</h5>
            <p class="mb-1 opacity-75 small">Petugas BPBD — Verifikasi &amp; tinjau laporan masyarakat</p>
            <small class="opacity-60 d-flex align-items-center gap-1">
                <span class="material-symbols-outlined ms-sm">info</span>
                {{ $stats['pending'] }} laporan baru menunggu tinjauan
            </small>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['label' => 'Pending',  'key' => 'pending',  'color' => 'warning',   'icon' => 'pending'],
        ['label' => 'Diproses', 'key' => 'diproses', 'color' => 'info',      'icon' => 'autorenew'],
        ['label' => 'Selesai',  'key' => 'selesai',  'color' => 'success',   'icon' => 'done_all'],
        ['label' => 'Hoaks',    'key' => 'hoaks',    'color' => 'secondary', 'icon' => 'block'],
        ['label' => 'Total',    'key' => 'total',    'color' => 'primary',   'icon' => 'chat'],
    ] as $item)
    <div class="col">
        <div class="stat-card" style="border-left-color: var(--bs-{{ $item['color'] }});">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 bg-{{ $item['color'] }} bg-opacity-10 text-{{ $item['color'] }}">
                    <span class="material-symbols-outlined msf">{{ $item['icon'] }}</span>
                </div>
                <div>
                    <div class="text-muted small">{{ $item['label'] }}</div>
                    <div class="fs-2 fw-black">{{ $stats[$item['key']] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="content-card h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined ms-sm text-primary">fact_check</span>Laporan Perlu Ditinjau
                </h5>
                <a href="{{ route('petugas.laporan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            @forelse($laporanTerbaru as $l)
            <div class="d-flex align-items-start gap-3 py-3 border-bottom">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                    style="width:40px;height:40px;background:{{ $l->status === 'pending' ? '#f59e0b' : '#0ea5e9' }};">
                    @if($l->status === 'pending')
                        <span class="material-symbols-outlined msf" style="font-size:18px;">schedule</span>
                    @else
                        <span class="material-symbols-outlined msf" style="font-size:18px;">autorenew</span>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $l->jenis_bencana }} — {{ Str::limit($l->lokasi_kejadian, 40) }}</div>
                    <small class="text-muted">oleh {{ $l->nama_pelapor }} · {{ $l->created_at->diffForHumans() }}</small>
                </div>
                <a href="{{ route('petugas.laporan.show', $l) }}" class="btn btn-sm btn-outline-primary flex-shrink-0">Tinjau</a>
            </div>
            @empty
            <div class="text-center text-muted py-4">
                <span class="material-symbols-outlined ms-xxl d-block mb-2 opacity-30">inbox</span>
                Tidak ada laporan pending.
            </div>
            @endforelse
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card h-100 p-4">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined ms-sm text-secondary">inventory_2</span>Arsip Hoaks Terbaru
            </h5>
            @forelse($arsipHoaksTerbaru as $h)
            <div class="py-2 border-bottom">
                <div class="fw-semibold small">{{ $h->laporan?->jenis_bencana ?? 'Laporan Dihapus' }}</div>
                <div class="text-muted" style="font-size:11px;">{{ Str::limit($h->alasan, 60) }}</div>
                <div class="text-muted" style="font-size:11px;">{{ $h->tanggal_arsip?->format('d/m/Y') }}</div>
            </div>
            @empty
            <div class="text-center text-muted py-3 small">Belum ada arsip hoaks.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
