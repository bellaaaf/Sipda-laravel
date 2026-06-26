@extends('layouts.admin')

@section('title', 'Laporan Masyarakat')

@push('styles')
<style>
.status-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid var(--bs-border-color);
    transition: all 0.18s;
    white-space: nowrap;
    color: var(--bs-secondary-color);
    background: var(--bs-tertiary-bg);
    cursor: pointer;
}
.status-tab:hover { transform: translateY(-1px); }
.status-tab.active { color: #fff !important; border-color: transparent !important; }
.status-tab .tab-count {
    background: rgba(255,255,255,0.25);
    border-radius: 10px;
    padding: 1px 7px;
    font-size: 0.72rem;
}
.status-tab:not(.active) .tab-count { background: var(--bs-border-color); }

.laporan-row { border-left: 3px solid transparent !important; }
.laporan-row.s-pending  { border-left-color: #f59e0b !important; }
.laporan-row.s-diproses { border-left-color: #0ea5e9 !important; }
.laporan-row.s-ditinjau { border-left-color: #6366f1 !important; }
.laporan-row.s-selesai  { border-left-color: #10b981 !important; }
.laporan-row.s-hoaks    { border-left-color: #6b7280 !important; }
.laporan-row.s-ditolak  { border-left-color: #ef4444 !important; }

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
</style>
@endpush

@section('content')

{{-- ── Header ────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-success ms-lg">chat</span>Laporan Masyarakat
        </h3>
        <p class="text-muted mb-0 small">Total {{ $laporan->count() }} laporan masuk</p>
    </div>
    {{-- Search --}}
    <div class="input-group" style="width:220px;">
        <span class="input-group-text bg-transparent">
            <span class="material-symbols-outlined ms-sm text-muted">search</span>
        </span>
        <input type="text" class="form-control" id="searchInput" placeholder="Cari pelapor, jenis, lokasi…">
    </div>
</div>

{{-- ── Status Tabs ───────────────────────────────────────── --}}
@php
$tabs = [
    ['value'=>'',         'label'=>'Semua',    'color'=>'#4f46e5', 'icon'=>'inbox'],
    ['value'=>'pending',  'label'=>'Pending',  'color'=>'#f59e0b', 'icon'=>'schedule'],
    ['value'=>'diproses', 'label'=>'Diproses', 'color'=>'#0ea5e9', 'icon'=>'autorenew'],
    ['value'=>'ditinjau', 'label'=>'Ditinjau', 'color'=>'#6366f1', 'icon'=>'manage_search'],
    ['value'=>'selesai',  'label'=>'Selesai',  'color'=>'#10b981', 'icon'=>'task_alt'],
    ['value'=>'hoaks',    'label'=>'Hoaks',    'color'=>'#6b7280', 'icon'=>'block'],
    ['value'=>'ditolak',  'label'=>'Ditolak',  'color'=>'#ef4444', 'icon'=>'cancel'],
];
@endphp

<div class="d-flex flex-wrap gap-2 mb-4">
    @foreach($tabs as $tab)
    @php $count = $tab['value'] === '' ? $laporan->count() : ($statusCounts[$tab['value']] ?? 0); @endphp
    <button type="button"
            class="status-tab {{ $tab['value'] === '' ? 'active' : '' }}"
            data-filter="{{ $tab['value'] }}"
            style="{{ $tab['value'] === '' ? 'background:'.$tab['color'].';' : '' }}"
            data-color="{{ $tab['color'] }}">
        <span class="material-symbols-outlined msf" style="font-size:14px;">{{ $tab['icon'] }}</span>
        {{ $tab['label'] }}
        <span class="tab-count" id="cnt-{{ $tab['value'] ?: 'all' }}">{{ $count }}</span>
    </button>
    @endforeach
</div>

{{-- ── Table Card ────────────────────────────────────────── --}}
<div class="content-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="laporanTable" style="min-width:760px;">
            <thead class="table-light">
                <tr>
                    <th class="ps-4" style="width:46px;">#</th>
                    <th style="min-width:170px;">Pelapor</th>
                    <th style="min-width:140px;">Jenis &amp; Keparahan</th>
                    <th style="min-width:150px;">Lokasi</th>
                    <th style="width:115px;">Status</th>
                    <th style="width:105px;">Waktu</th>
                    <th class="text-end pe-4" style="width:95px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan as $i => $l)
                @php
                    $avatarColors = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'];
                    $avatarBg = $avatarColors[crc32($l->nama_pelapor) % count($avatarColors)];
                    $initials = strtoupper(collect(explode(' ', $l->nama_pelapor))->filter()->take(2)->map(fn($w)=>$w[0])->join(''));
                    $statusCls = 's-' . ($l->status ?? 'pending');
                    $statusMeta = match($l->status ?? 'pending') {
                        'pending'  => ['bg'=>'warning', 'icon'=>'schedule',       'label'=>'Pending',  'dark'=>true],
                        'diproses' => ['bg'=>'info',    'icon'=>'autorenew',      'label'=>'Diproses', 'dark'=>false],
                        'ditinjau' => ['bg'=>'primary',  'icon'=>'manage_search', 'label'=>'Ditinjau', 'dark'=>false],
                        'selesai'  => ['bg'=>'success',  'icon'=>'task_alt',      'label'=>'Selesai',  'dark'=>false],
                        'hoaks'    => ['bg'=>'secondary','icon'=>'block',         'label'=>'Hoaks',    'dark'=>false],
                        'ditolak'  => ['bg'=>'danger',   'icon'=>'cancel',        'label'=>'Ditolak',  'dark'=>false],
                        default    => ['bg'=>'secondary','icon'=>'help',          'label'=>ucfirst($l->status), 'dark'=>false],
                    };
                    $keparahanMeta = match($l->tingkat_keparahan ?? '') {
                        'Sangat Berat' => ['bg'=>'danger', 'label'=>'Sangat Berat'],
                        'Berat'        => ['bg'=>'warning','label'=>'Berat'],
                        'Sedang'       => ['bg'=>'info',   'label'=>'Sedang'],
                        default        => ['bg'=>'success','label'=>$l->tingkat_keparahan ?? 'Ringan'],
                    };
                @endphp
                <tr class="laporan-row {{ $statusCls }}" data-status="{{ $l->status }}">
                    <td class="ps-4 text-muted small fw-semibold">{{ $i + 1 }}</td>

                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="pelapor-avatar" style="background:{{ $avatarBg }};">{{ $initials }}</div>
                            <div style="min-width:0;">
                                <div class="fw-semibold text-truncate" style="max-width:130px;font-size:0.875rem;">{{ $l->nama_pelapor }}</div>
                                <div class="text-muted" style="font-size:0.75rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:130px;">{{ $l->email_pelapor }}</div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="fw-semibold small mb-1">{{ $l->jenis_bencana }}</div>
                        <span class="badge bg-{{ $keparahanMeta['bg'] }} rounded-pill" style="font-size:10px;">{{ $keparahanMeta['label'] }}</span>
                    </td>

                    <td>
                        <div class="d-flex align-items-start gap-1">
                            <span class="material-symbols-outlined msf text-danger ms-sm flex-shrink-0" style="margin-top:2px;">location_on</span>
                            <span class="small text-muted">{{ Str::limit($l->lokasi_kejadian, 38) }}</span>
                        </div>
                    </td>

                    <td>
                        <span class="badge bg-{{ $statusMeta['bg'] }} d-inline-flex align-items-center gap-1 rounded-pill px-2"
                              style="font-size:11px;{{ $statusMeta['dark'] ? 'color:#1a1a1a;' : '' }}">
                            <span class="material-symbols-outlined msf" style="font-size:12px;">{{ $statusMeta['icon'] }}</span>
                            {{ $statusMeta['label'] }}
                        </span>
                    </td>

                    <td>
                        <div class="small fw-semibold">{{ $l->created_at->format('d M Y') }}</div>
                        <div class="text-muted" style="font-size:11px;">{{ $l->created_at->format('H:i') }}</div>
                    </td>

                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.laporan.show', $l) }}"
                               class="btn btn-sm btn-outline-primary d-inline-flex align-items-center"
                               title="Lihat Detail">
                                <span class="material-symbols-outlined ms-sm">visibility</span>
                            </a>
                            <form action="{{ route('admin.laporan.destroy', $l) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center"
                                        onclick="confirmDelete(this.closest('form'), 'Hapus Laporan', 'Laporan dari {{ addslashes($l->nama_pelapor) }} akan dihapus permanen.')"
                                        title="Hapus">
                                    <span class="material-symbols-outlined ms-sm">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="7" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                             style="width:72px;height:72px;background:var(--bs-tertiary-bg);">
                            <span class="material-symbols-outlined ms-xxl text-muted opacity-40">inbox</span>
                        </div>
                        <div class="fw-semibold mb-1">Belum ada laporan</div>
                        <div class="text-muted small">Laporan dari masyarakat akan muncul di sini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Row count info --}}
    <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="text-muted small" id="rowInfo">Menampilkan {{ $laporan->count() }} laporan</div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const rows     = Array.from(document.querySelectorAll('#laporanTable tbody tr[data-status]'));
    const infoEl   = document.getElementById('rowInfo');
    let activeFilter = '';
    let searchQuery  = '';

    function applyFilters() {
        let visible = 0;
        rows.forEach(row => {
            const matchStatus = !activeFilter || row.dataset.status === activeFilter;
            const matchSearch = !searchQuery  || row.textContent.toLowerCase().includes(searchQuery);
            const show = matchStatus && matchSearch;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        infoEl.textContent = 'Menampilkan ' + visible + ' laporan';
    }

    // Status tabs
    document.querySelectorAll('.status-tab').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.status-tab').forEach(b => {
                b.classList.remove('active');
                b.style.background = '';
                b.style.color = '';
            });
            this.classList.add('active');
            this.style.background = this.dataset.color;
            this.style.color = '#fff';
            activeFilter = this.dataset.filter;
            applyFilters();
        });
    });

    // Search
    document.getElementById('searchInput').addEventListener('input', function () {
        searchQuery = this.value.toLowerCase().trim();
        applyFilters();
    });
})();

function confirmDelete(form, title, text) {
    Swal.fire({
        title: title || 'Hapus data ini?',
        text: text || 'Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        iconColor: '#ef4444',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then(r => { if (r.isConfirmed) form.submit(); });
}
</script>
@endpush
