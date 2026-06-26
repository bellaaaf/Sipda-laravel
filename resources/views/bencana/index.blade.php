@extends('layouts.app')

@section('title', 'Data Bencana')

@push('styles')
<style>
/* ── Map legend ─────────────────────────────────────────── */
.map-legend {
    background: #fff;
    border-radius: 12px;
    padding: 11px 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    font-family: 'Inter', sans-serif;
    min-width: 128px;
    border: 1px solid rgba(0,0,0,0.06);
}
[data-bs-theme="dark"] .map-legend {
    background: #1e293b;
    border-color: rgba(255,255,255,0.07);
}
.map-legend-title {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.9px;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 8px;
}
[data-bs-theme="dark"] .map-legend-title { color: #94a3b8; }
.map-legend-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 500;
    color: #0f172a;
}
[data-bs-theme="dark"] .map-legend-row { color: #e2e8f0; }
.map-legend-row:last-child { margin-bottom: 0; }
.map-legend-dot {
    width: 11px; height: 11px;
    border-radius: 50%;
    flex-shrink: 0;
    box-shadow: 0 1px 4px rgba(0,0,0,0.18);
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-1 d-flex align-items-center gap-2">
        <span class="material-symbols-outlined msf text-danger ms-lg">warning</span>Data Bencana
    </h2>
    <p class="text-muted mb-4">Informasi bencana alam terkini di Kota Bandung</p>

    {{-- Peta --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined msf ms-sm text-primary">map</span>Peta Sebaran Bencana
            </h6>
            <span class="text-muted small d-flex align-items-center gap-1">
                <span class="material-symbols-outlined msf ms-sm">location_on</span>
                {{ $mapData->count() }} titik terpetakan — hover untuk info, klik untuk detail
            </span>
        </div>
        <div class="card-body p-0">
            <div id="map" style="height:480px;border-radius:0 0 1rem 1rem;"></div>
        </div>
    </div>

    {{-- Daftar bencana --}}
    <div class="row g-4">
        @forelse($bencana as $b)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-{{ $b->status_color }} rounded-pill">{{ $b->tingkat_status }}</span>
                        <small class="text-muted d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined ms-sm">calendar_month</span>{{ $b->tanggal_kejadian->format('d M Y') }}
                        </small>
                    </div>
                    <h5 class="fw-bold">{{ $b->jenis?->nama_bencana ?? 'Bencana' }}</h5>
                    <p class="text-muted small d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined msf ms-sm text-danger">location_on</span>{{ $b->lokasi }}
                    </p>
                    <p class="text-muted small mb-0">{{ Str::limit($b->deskripsi, 100) }}</p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('bencana.show', $b) }}" class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center gap-1">
                        <span class="material-symbols-outlined ms-sm">arrow_forward_ios</span>Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <span class="material-symbols-outlined msf text-success ms-xxl d-block mb-3">verified_user</span>
            <h4>Tidak Ada Bencana Aktif</h4>
            <p class="text-muted">Kondisi Kota Bandung saat ini aman.</p>
        </div>
        @endforelse
    </div>

    @if($bencana->hasPages())
    <div class="d-flex justify-content-center mt-4">{{ $bencana->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    const STATUS_COLORS = {
        Darurat: '#ef4444',
        Siaga:   '#3b82f6',
        Waspada: '#f59e0b',
        Aman:    '#10b981'
    };

    // ── Tile layers (CartoDB — lebih bersih dari OSM default) ──
    const lightTiles = L.tileLayer(
        'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
        { attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>', maxZoom: 19 }
    );
    const darkTiles = L.tileLayer(
        'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
        { attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/">CARTO</a>', maxZoom: 19 }
    );

    const map = L.map('map').setView([-6.9175, 107.6191], 12);

    let activeTiles = document.documentElement.getAttribute('data-bs-theme') === 'dark'
        ? darkTiles : lightTiles;
    activeTiles.addTo(map);

    // Sinkron tile dengan dark/light mode toggle
    document.getElementById('themeToggle')?.addEventListener('click', () => {
        setTimeout(() => {
            const theme = document.documentElement.getAttribute('data-bs-theme');
            map.removeLayer(activeTiles);
            activeTiles = theme === 'dark' ? darkTiles : lightTiles;
            activeTiles.addTo(map);
        }, 50);
    });

    // ── Custom pin icon ────────────────────────────────────────
    function createPinIcon(status) {
        const color   = STATUS_COLORS[status] || '#6b7280';
        const isPulse = status === 'Darurat';
        return L.divIcon({
            className: '',
            html: `<div class="map-pin${isPulse ? ' map-pin-pulse' : ''}" style="--pin-color:${color}">
                       ${isPulse ? '<div class="map-pin-ring"></div>' : ''}
                       <div class="map-pin-body"></div>
                   </div>`,
            iconSize:     [32, 42],
            iconAnchor:   [16, 42],
            popupAnchor:  [0, -46],
            tooltipAnchor:[0, -46]
        });
    }

    // ── Render markers ─────────────────────────────────────────
    const mapData    = @json($mapData);
    const markerList = [];

    mapData.forEach(b => {
        if (!b.latitude || !b.longitude) return;

        const color   = STATUS_COLORS[b.tingkat_status] || '#6b7280';
        const jenis   = b.jenis?.nama_bencana ?? 'Bencana';
        const tanggal = b.tanggal_kejadian ?? '';
        const teksWarna = b.tingkat_status === 'Waspada' ? '#1a1a1a' : '#fff';

        const marker = L.marker([b.latitude, b.longitude], {
            icon: createPinIcon(b.tingkat_status)
        }).addTo(map);

        // ── Hover tooltip ──────────────────────────────────────
        marker.bindTooltip(`
            <div style="font-family:'Inter',sans-serif;min-width:195px;line-height:1.4;">
                <div style="font-weight:700;font-size:13px;margin-bottom:5px;">${jenis}</div>
                <div style="font-size:12px;color:#64748b;margin-bottom:7px;display:flex;gap:4px;align-items:flex-start;">
                    <span style="flex-shrink:0;">📍</span><span>${b.lokasi}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                    <span style="background:${color};color:${teksWarna};border-radius:20px;padding:2px 10px;font-size:11px;font-weight:600;">${b.tingkat_status}</span>
                    <span style="font-size:11px;color:#94a3b8;">📅 ${tanggal}</span>
                </div>
            </div>
        `, {
            direction: 'top',
            opacity:   1,
            className: 'sipda-tooltip'
        });

        // ── Click popup ────────────────────────────────────────
        marker.bindPopup(`
            <div style="font-family:'Inter',sans-serif;min-width:215px;padding:2px 0;line-height:1.4;">
                <div style="font-weight:700;font-size:14px;margin-bottom:6px;">${jenis}</div>
                <div style="font-size:12px;color:#64748b;margin-bottom:4px;display:flex;gap:5px;align-items:flex-start;">
                    <span style="flex-shrink:0;">📍</span><span>${b.lokasi}</span>
                </div>
                <div style="font-size:11px;color:#94a3b8;margin-bottom:10px;">📅 ${tanggal}</div>
                <hr style="margin:0 0 10px;border-color:#e2e8f0;">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="background:${color};color:${teksWarna};border-radius:20px;padding:3px 12px;font-size:11px;font-weight:600;">${b.tingkat_status}</span>
                    <a href="/bencana/${b.id}"
                       style="color:#3b82f6;font-size:12px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:2px;">
                        Lihat Detail &rsaquo;
                    </a>
                </div>
            </div>
        `, { maxWidth: 270 });

        markerList.push(marker);
    });

    // Auto-fit zoom untuk tampilkan semua marker
    if (markerList.length > 0) {
        map.fitBounds(L.featureGroup(markerList).getBounds().pad(0.15));
    }

    // ── Legend ─────────────────────────────────────────────────
    const legend = L.control({ position: 'bottomright' });
    legend.onAdd = function () {
        const div = L.DomUtil.create('div', 'map-legend');
        div.innerHTML = `
            <div class="map-legend-title">Status Bencana</div>
            ${Object.entries(STATUS_COLORS).map(([status, color]) => `
                <div class="map-legend-row">
                    <span class="map-legend-dot" style="background:${color};"></span>
                    ${status}
                </div>
            `).join('')}
        `;
        return div;
    };
    legend.addTo(map);

})();
</script>
@endpush
