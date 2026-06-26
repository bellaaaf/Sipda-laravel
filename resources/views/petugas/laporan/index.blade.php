@extends('layouts.petugas')

@section('title', 'Verifikasi Laporan')

@push('styles')
<style>
/* Status quick-filter tabs */
.status-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid transparent;
    transition: all 0.18s;
    white-space: nowrap;
    color: var(--bs-secondary-color);
    background: var(--bs-tertiary-bg);
    border-color: var(--bs-border-color);
}
.status-tab:hover { transform: translateY(-1px); }
.status-tab.active {
    color: #fff !important;
    border-color: transparent !important;
}
.status-tab .tab-count {
    background: rgba(255,255,255,0.25);
    border-radius: 10px;
    padding: 1px 7px;
    font-size: 0.72rem;
}
.status-tab:not(.active) .tab-count {
    background: var(--bs-border-color);
}

/* Table row left-border accent */
.laporan-row { border-left: 3px solid transparent; }
.laporan-row.s-pending  { border-left-color: #f59e0b; }
.laporan-row.s-diproses { border-left-color: #0ea5e9; }
.laporan-row.s-ditinjau { border-left-color: #6366f1; }
.laporan-row.s-selesai  { border-left-color: #10b981; }
.laporan-row.s-hoaks    { border-left-color: #6b7280; }
.laporan-row.s-ditolak  { border-left-color: #ef4444; }

/* Pelapor avatar */
.pelapor-avatar {
    width: 36px; height: 36px;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #fff;
}

/* Filter card */
.filter-card {
    border-radius: 14px;
    border: 1px solid var(--bs-border-color);
    padding: 14px 18px;
    background: var(--bs-tertiary-bg);
    margin-bottom: 1.25rem;
}
</style>
@endpush

@section('content')

{{-- ── Page Header ───────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-primary ms-lg">fact_check</span>Verifikasi Laporan
        </h3>
        <p class="text-muted mb-0 small">
            Total {{ $laporan->total() }} laporan
            @if(request('status')) · Filter: <span class="fw-semibold">{{ ucfirst(request('status')) }}</span> @endif
            @if(request('dari') || request('sampai')) · Rentang tanggal terpilih @endif
        </p>
    </div>
    <a href="{{ route('petugas.laporan.export') }}?{{ http_build_query(request()->all()) }}"
       class="btn btn-success d-flex align-items-center gap-2">
        <span class="material-symbols-outlined ms-sm">download</span>Export Excel
    </a>
</div>

{{-- ── Status Quick Filters ──────────────────────────────── --}}
@php
$tabs = [
    ['value' => '',         'label' => 'Semua',    'color' => '#4f46e5', 'icon' => 'inbox'],
    ['value' => 'pending',  'label' => 'Pending',  'color' => '#f59e0b', 'icon' => 'schedule'],
    ['value' => 'diproses', 'label' => 'Diproses', 'color' => '#0ea5e9', 'icon' => 'autorenew'],
    ['value' => 'ditinjau', 'label' => 'Ditinjau', 'color' => '#6366f1', 'icon' => 'manage_search'],
    ['value' => 'selesai',  'label' => 'Selesai',  'color' => '#10b981', 'icon' => 'task_alt'],
    ['value' => 'hoaks',    'label' => 'Hoaks',    'color' => '#6b7280', 'icon' => 'block'],
    ['value' => 'ditolak',  'label' => 'Ditolak',  'color' => '#ef4444', 'icon' => 'cancel'],
];
$currentStatus = request('status', '');
$totalAll = $statusCounts->sum();
@endphp

<div class="d-flex flex-wrap gap-2 mb-4">
    @foreach($tabs as $tab)
    @php
        $isActive = $currentStatus === $tab['value'];
        $count = $tab['value'] === '' ? $totalAll : ($statusCounts[$tab['value']] ?? 0);
        $params = array_merge(request()->except('status','page'), $tab['value'] ? ['status' => $tab['value']] : []);
    @endphp
    <a href="{{ route('petugas.laporan.index') }}?{{ http_build_query($params) }}"
       class="status-tab {{ $isActive ? 'active' : '' }}"
       style="{{ $isActive ? 'background:'.$tab['color'].';' : '' }}">
        <span class="material-symbols-outlined msf" style="font-size:14px;">{{ $tab['icon'] }}</span>
        {{ $tab['label'] }}
        <span class="tab-count">{{ $count }}</span>
    </a>
    @endforeach
</div>

{{-- ── Filter Bar ────────────────────────────────────────── --}}
<div class="filter-card">
    <form method="GET" class="d-flex align-items-center gap-2 flex-wrap">
        @if(request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <span class="material-symbols-outlined msf text-muted ms-sm">date_range</span>
        <label class="text-muted small fw-semibold mb-0" style="white-space:nowrap;">Rentang Tanggal:</label>
        <input type="date" name="dari" class="form-control form-control-sm"
               value="{{ request('dari') }}" style="width:138px;">
        <span class="text-muted small">—</span>
        <input type="date" name="sampai" class="form-control form-control-sm"
               value="{{ request('sampai') }}" style="width:138px;">
        <button type="submit" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
            <span class="material-symbols-outlined ms-sm">filter_list</span>Terapkan
        </button>
        @if(request('dari') || request('sampai'))
        <a href="{{ route('petugas.laporan.index') }}{{ request('status') ? '?status='.request('status') : '' }}"
           class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
            <span class="material-symbols-outlined ms-sm">close</span>Reset
        </a>
        @endif
    </form>
</div>

{{-- ── Table Card ────────────────────────────────────────── --}}
<div class="content-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="min-width:780px;">
            <thead class="table-light">
                <tr>
                    <th class="ps-4" style="width:46px;">#</th>
                    <th style="min-width:170px;">Pelapor</th>
                    <th style="min-width:140px;">Jenis &amp; Keparahan</th>
                    <th style="min-width:160px;">Lokasi</th>
                    <th style="width:110px;">Status</th>
                    <th style="width:100px;">Waktu</th>
                    <th class="text-end pe-4" style="width:90px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $l)
                @php
                    $avatarColors = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'];
                    $avatarBg = $avatarColors[crc32($l->nama_pelapor) % count($avatarColors)];
                    $initials = strtoupper(collect(explode(' ', $l->nama_pelapor))->filter()->take(2)->map(fn($w)=>$w[0])->join(''));
                    $statusCls = 's-' . ($l->status ?? 'pending');
                    $statusMeta = match($l->status ?? 'pending') {
                        'pending'  => ['bg'=>'warning','icon'=>'schedule',      'label'=>'Pending',  'dark'=>true],
                        'diproses' => ['bg'=>'info',   'icon'=>'autorenew',     'label'=>'Diproses', 'dark'=>false],
                        'ditinjau' => ['bg'=>'primary', 'icon'=>'manage_search','label'=>'Ditinjau', 'dark'=>false],
                        'selesai'  => ['bg'=>'success', 'icon'=>'task_alt',     'label'=>'Selesai',  'dark'=>false],
                        'hoaks'    => ['bg'=>'secondary','icon'=>'block',       'label'=>'Hoaks',    'dark'=>false],
                        'ditolak'  => ['bg'=>'danger',  'icon'=>'cancel',      'label'=>'Ditolak',  'dark'=>false],
                        default    => ['bg'=>'secondary','icon'=>'help',        'label'=>ucfirst($l->status), 'dark'=>false],
                    };
                    $keparahanMeta = match($l->tingkat_keparahan ?? '') {
                        'Sangat Berat' => ['bg'=>'danger', 'label'=>'Sangat Berat'],
                        'Berat'        => ['bg'=>'warning','label'=>'Berat'],
                        'Sedang'       => ['bg'=>'info',   'label'=>'Sedang'],
                        default        => ['bg'=>'success','label'=>$l->tingkat_keparahan ?? 'Ringan'],
                    };
                @endphp
                <tr class="laporan-row {{ $statusCls }}">
                    {{-- No --}}
                    <td class="ps-4 text-muted small fw-semibold">
                        {{ $loop->iteration + ($laporan->currentPage() - 1) * $laporan->perPage() }}
                    </td>

                    {{-- Pelapor --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="pelapor-avatar" style="background:{{ $avatarBg }};">{{ $initials }}</div>
                            <div style="min-width:0;">
                                <div class="fw-semibold text-truncate" style="max-width:130px;font-size:0.875rem;">{{ $l->nama_pelapor }}</div>
                                <div class="text-muted" style="font-size:0.75rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:130px;">{{ $l->email_pelapor }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Jenis & Keparahan --}}
                    <td>
                        <div class="fw-semibold small mb-1">{{ $l->jenis_bencana }}</div>
                        <span class="badge bg-{{ $keparahanMeta['bg'] }} rounded-pill" style="font-size:10px;">
                            {{ $keparahanMeta['label'] }}
                        </span>
                    </td>

                    {{-- Lokasi --}}
                    <td>
                        <div class="d-flex align-items-start gap-1">
                            <span class="material-symbols-outlined msf text-danger ms-sm flex-shrink-0" style="margin-top:2px;">location_on</span>
                            <span class="small text-muted">{{ Str::limit($l->lokasi_kejadian, 38) }}</span>
                        </div>
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="badge bg-{{ $statusMeta['bg'] }} d-inline-flex align-items-center gap-1 rounded-pill px-2"
                              style="font-size:11px;{{ $statusMeta['dark'] ? 'color:#1a1a1a;' : '' }}">
                            <span class="material-symbols-outlined msf" style="font-size:12px;">{{ $statusMeta['icon'] }}</span>
                            {{ $statusMeta['label'] }}
                        </span>
                    </td>

                    {{-- Waktu --}}
                    <td>
                        <div class="small fw-semibold">{{ $l->created_at->format('d M') }}</div>
                        <div class="text-muted" style="font-size:11px;">{{ $l->created_at->format('H:i') }}</div>
                    </td>

                    {{-- Aksi --}}
                    <td class="text-end pe-4">
                        <a href="{{ route('petugas.laporan.show', $l) }}"
                           class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                            <span class="material-symbols-outlined ms-sm">rate_review</span>Tinjau
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                             style="width:72px;height:72px;background:var(--bs-tertiary-bg);">
                            <span class="material-symbols-outlined ms-xxl text-muted opacity-40">inbox</span>
                        </div>
                        <div class="fw-semibold mb-1">Tidak ada laporan ditemukan</div>
                        <div class="text-muted small">
                            @if(request('status') || request('dari') || request('sampai'))
                                Coba ubah filter atau
                                <a href="{{ route('petugas.laporan.index') }}">reset semua filter</a>
                            @else
                                Belum ada laporan masuk saat ini.
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($laporan->hasPages())
    <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="text-muted small">
            Menampilkan {{ $laporan->firstItem() }}–{{ $laporan->lastItem() }} dari {{ $laporan->total() }} laporan
        </div>
        {{ $laporan->links() }}
    </div>
    @endif
</div>

@endsection
