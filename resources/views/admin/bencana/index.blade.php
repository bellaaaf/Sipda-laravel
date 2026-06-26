@extends('layouts.admin')

@section('title', 'Kelola Data Bencana')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
#bencanaMap {
    height: 300px;
    border-radius: 10px;
    border: 1px solid var(--bs-border-color);
    z-index: 0;
    position: relative;
}
.location-search-wrapper { position: relative; }
#searchResults {
    position: absolute; top: 100%; left: 0; right: 0;
    z-index: 10000;
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-top: none;
    border-radius: 0 0 8px 8px;
    max-height: 200px;
    overflow-y: auto;
    display: none;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
}
#searchResults .srch-item {
    padding: 9px 14px;
    cursor: pointer;
    font-size: 0.82rem;
    border-bottom: 1px solid var(--bs-border-color);
    transition: background 0.1s;
}
#searchResults .srch-item:hover { background: var(--bs-secondary-bg); }
#searchResults .srch-item:last-child { border-bottom: none; }
#gpsBtn.gps-tracking {
    animation: gpsPulse 1.2s ease-in-out infinite;
}
@keyframes gpsPulse { 0%,100%{opacity:1} 50%{opacity:.45} }
.gps-accuracy-badge {
    font-size: 0.7rem;
    padding: 2px 7px;
    border-radius: 20px;
    background: var(--bs-success-bg-subtle);
    color: var(--bs-success);
    border: 1px solid var(--bs-success-border-subtle);
    white-space: nowrap;
}
.map-tip {
    font-size: 0.75rem;
    color: var(--bs-secondary-color);
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 5px;
}
.leaflet-container { font-family: 'Inter', sans-serif; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <span class="material-symbols-outlined text-primary">database</span>Data Bencana
    </h3>
    <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <span class="material-symbols-outlined ms-sm">add_circle</span>Tambah Bencana
    </button>
</div>

<div class="content-card">
    <div class="p-3">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Jenis</th><th>Lokasi</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($bencana as $i => $b)
                    <tr class="{{ $b->tingkat_status === 'Aman' ? 'table-success opacity-75' : '' }}">
                        <td>{{ $i+1 }}</td>
                        <td class="fw-semibold">{{ $b->jenis->nama_bencana ?? '-' }}</td>
                        <td>{{ Str::limit($b->lokasi, 40) }}</td>
                        <td>
                            <span class="badge bg-{{ $b->status_color }} rounded-pill">{{ $b->tingkat_status }}</span>
                        </td>
                        <td class="text-muted small">{{ $b->tanggal_kejadian->format('d M Y') }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1 d-inline-flex align-items-center" onclick="editBencana({{ $b->id }})">
                                <span class="material-symbols-outlined ms-sm">edit</span>
                            </button>
                            <form action="{{ route('admin.bencana.destroy', $b) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center"
                                    onclick="confirmDelete(this.closest('form'), 'Hapus Data Bencana', 'Data bencana di {{ addslashes(Str::limit($b->lokasi, 30)) }} akan dihapus permanen.')">
                                    <span class="material-symbols-outlined ms-sm">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <span class="material-symbols-outlined ms-xxl d-block mb-2 opacity-30">inbox</span>
                            Belum ada data bencana.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah / Edit Bencana --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <div class="modal-icon-wrap me-3" id="modalIconWrap">
                    <span class="material-symbols-outlined msf ms-sm">add_circle</span>
                </div>
                <div class="flex-grow-1">
                    <h5 class="modal-title mb-0" id="modalTitle">Tambah Data Bencana</h5>
                    <div class="modal-subtitle" id="modalSubtitle">Isi form untuk menambahkan data bencana baru</div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal"></button>
            </div>
            <form id="formBencana" method="POST" action="{{ route('admin.bencana.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-body">

                    <div class="modal-section-label">
                        <span class="material-symbols-outlined ms-sm">info</span>Informasi Bencana
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Jenis Bencana <span class="text-danger">*</span></label>
                            <select name="jenis_id" class="form-select" required>
                                <option value="">— Pilih Jenis —</option>
                                @foreach($jenis as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_bencana }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tingkat Status <span class="text-danger">*</span></label>
                            <select name="tingkat_status" class="form-select" required>
                                <option value="Waspada">Waspada</option>
                                <option value="Siaga">Siaga</option>
                                <option value="Darurat">Darurat</option>
                                <option value="Aman">Aman</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-label">
                            <span class="material-symbols-outlined ms-sm">location_on</span>Lokasi &amp; Waktu
                        </div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Lokasi Kejadian <span class="text-danger">*</span></label>
                                <input type="text" name="lokasi" id="inputLokasi" class="form-control" placeholder="Alamat atau lokasi kejadian bencana" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_kejadian" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Latitude</label>
                                <input type="number" name="latitude" id="inputLatitude" class="form-control" placeholder="-6.9175" step="any">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Longitude</label>
                                <input type="number" name="longitude" id="inputLongitude" class="form-control" placeholder="107.6191" step="any">
                            </div>
                        </div>

                        {{-- Map Picker --}}
                        <div class="mt-3">
                            <div class="modal-section-label mb-2">
                                <span class="material-symbols-outlined ms-sm">map</span>Titik Lokasi di Peta
                            </div>

                            {{-- Search + GPS controls --}}
                            <div class="d-flex gap-2 mb-2 flex-wrap">
                                <div class="location-search-wrapper flex-grow-1">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent">
                                            <span class="material-symbols-outlined ms-sm">search</span>
                                        </span>
                                        <input type="text" id="locationSearch" class="form-control"
                                               placeholder="Cari nama jalan, kelurahan, kota..."
                                               autocomplete="off"
                                               oninput="onSearchInput()"
                                               onkeydown="if(event.key==='Enter'){event.preventDefault();doSearch();}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="doSearch()">Cari</button>
                                    </div>
                                    <div id="searchResults"></div>
                                </div>
                                <button type="button" class="btn btn-outline-primary d-flex align-items-center gap-1 flex-shrink-0" id="gpsBtn" onclick="toggleGPS()">
                                    <span class="material-symbols-outlined ms-sm">my_location</span>
                                    <span id="gpsLabel">Lokasi Saya</span>
                                </button>
                            </div>

                            {{-- GPS accuracy badge --}}
                            <div id="gpsAccuracy" class="mb-2" style="display:none;">
                                <span class="gps-accuracy-badge">
                                    <span class="material-symbols-outlined ms-sm">gps_fixed</span>
                                    Tracking aktif — akurasi: <span id="gpsAccuracyVal">?</span>m
                                </span>
                            </div>

                            {{-- Map container --}}
                            <div id="bencanaMap"></div>

                            <div class="map-tip">
                                <span class="material-symbols-outlined ms-sm">info</span>
                                Klik peta untuk menentukan titik, drag marker untuk memindahkan, atau gunakan pencarian &amp; GPS di atas.
                                Alamat terdeteksi otomatis akan mengisi kolom Lokasi Kejadian.
                            </div>
                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-label">
                            <span class="material-symbols-outlined ms-sm">description</span>Detail &amp; Media
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                <textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi lengkap kejadian bencana..." required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Foto <span class="text-muted fw-normal" style="text-transform:none;">(opsional)</span></label>
                                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-1" data-bs-dismiss="modal">
                        <span class="material-symbols-outlined ms-sm">close</span>Batal
                    </button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf ms-sm">save</span>Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
/* ── Data ───────────────────────────────────── */
const bencanaData = @json($bencana->keyBy('id'));

/* ── Map state ──────────────────────────────── */
const BANDUNG = [-6.9175, 107.6191];
let bencanaMap    = null;
let bencanaMarker = null;
let gpsWatchId    = null;
let searchTimer   = null;

/* ── Map init ───────────────────────────────── */
function initMap(lat, lng) {
    const hasCoord = lat && lng;
    const center   = hasCoord ? [parseFloat(lat), parseFloat(lng)] : BANDUNG;
    const zoom     = hasCoord ? 15 : 12;

    if (!bencanaMap) {
        bencanaMap = L.map('bencanaMap', { zoomControl: true }).setView(center, zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://openstreetmap.org">OpenStreetMap</a>',
            maxZoom: 19
        }).addTo(bencanaMap);

        bencanaMap.on('click', function (e) {
            applyLocation(e.latlng.lat, e.latlng.lng, true);
        });
    } else {
        bencanaMap.setView(center, zoom);
    }

    if (hasCoord) {
        placeMarker(parseFloat(lat), parseFloat(lng));
    } else if (bencanaMarker) {
        bencanaMarker.remove();
        bencanaMarker = null;
    }

    setTimeout(() => bencanaMap && bencanaMap.invalidateSize(), 250);
}

function placeMarker(lat, lng) {
    if (bencanaMarker) {
        bencanaMarker.setLatLng([lat, lng]);
    } else {
        bencanaMarker = L.marker([lat, lng], { draggable: true }).addTo(bencanaMap);
        bencanaMarker.on('dragend', function (e) {
            const p = e.target.getLatLng();
            fillLatLng(p.lat, p.lng);
            reverseGeocode(p.lat, p.lng);
        });
    }
}

/* ── Apply location (from map click / GPS / search) ── */
function applyLocation(lat, lng, doReverse) {
    fillLatLng(lat, lng);
    if (bencanaMap) {
        bencanaMap.setView([lat, lng], 15);
    }
    placeMarker(lat, lng);
    if (doReverse) reverseGeocode(lat, lng);
}

function fillLatLng(lat, lng) {
    document.getElementById('inputLatitude').value  = parseFloat(lat).toFixed(6);
    document.getElementById('inputLongitude').value = parseFloat(lng).toFixed(6);
}

/* ── Reverse geocode → fill lokasi field ─────── */
async function reverseGeocode(lat, lng) {
    try {
        const r = await fetch(
            `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=id`,
            { headers: { 'Accept-Language': 'id' } }
        );
        const d = await r.json();
        if (d && d.display_name) {
            document.getElementById('inputLokasi').value = d.display_name;
        }
    } catch (_) {}
}

/* ── GPS tracking ────────────────────────────── */
function toggleGPS() {
    if (!navigator.geolocation) {
        Swal.fire('Tidak Didukung', 'Browser Anda tidak mendukung fitur GPS.', 'error');
        return;
    }

    if (gpsWatchId !== null) {
        stopGPS();
        return;
    }

    const btn   = document.getElementById('gpsBtn');
    const label = document.getElementById('gpsLabel');
    const acc   = document.getElementById('gpsAccuracy');

    label.textContent = 'Mendapatkan…';
    btn.disabled      = true;

    gpsWatchId = navigator.geolocation.watchPosition(
        (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            const m   = Math.round(pos.coords.accuracy);

            applyLocation(lat, lng, true);

            btn.disabled = false;
            btn.classList.add('btn-danger', 'gps-tracking');
            btn.classList.remove('btn-outline-primary');
            label.textContent = 'Hentikan GPS';
            acc.style.display = 'block';
            document.getElementById('gpsAccuracyVal').textContent = m;
        },
        (err) => {
            stopGPS();
            const msgs = {
                1: 'Akses lokasi ditolak. Izinkan akses lokasi di pengaturan browser.',
                2: 'Sinyal GPS tidak tersedia. Pastikan GPS perangkat aktif.',
                3: 'Waktu habis mendapatkan lokasi. Coba lagi.'
            };
            Swal.fire('GPS Gagal', msgs[err.code] || 'Lokasi tidak bisa didapatkan.', 'warning');
        },
        { enableHighAccuracy: true, timeout: 20000, maximumAge: 0 }
    );
}

function stopGPS() {
    if (gpsWatchId !== null) {
        navigator.geolocation.clearWatch(gpsWatchId);
        gpsWatchId = null;
    }
    const btn   = document.getElementById('gpsBtn');
    const label = document.getElementById('gpsLabel');
    const acc   = document.getElementById('gpsAccuracy');

    btn.disabled = false;
    btn.classList.remove('btn-danger', 'gps-tracking');
    btn.classList.add('btn-outline-primary');
    label.textContent     = 'Lokasi Saya';
    acc.style.display     = 'none';
}

/* ── Location search (Nominatim) ─────────────── */
function onSearchInput() {
    clearTimeout(searchTimer);
    const q = document.getElementById('locationSearch').value.trim();
    if (q.length < 3) {
        hideSearchResults();
        return;
    }
    searchTimer = setTimeout(doSearch, 500);
}

function doSearch() {
    const q = document.getElementById('locationSearch').value.trim();
    if (!q) return;
    fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(q)}&format=json&limit=5&accept-language=id&countrycodes=id`)
        .then(r => r.json())
        .then(renderResults)
        .catch(() => {});
}

function renderResults(results) {
    const box = document.getElementById('searchResults');
    box.innerHTML = '';

    if (!results.length) {
        box.innerHTML = '<div class="srch-item text-muted">Lokasi tidak ditemukan</div>';
        box.style.display = 'block';
        return;
    }

    results.forEach(item => {
        const el = document.createElement('div');
        el.className   = 'srch-item';
        el.textContent = item.display_name;
        el.addEventListener('click', () => {
            document.getElementById('locationSearch').value = '';
            document.getElementById('inputLokasi').value   = item.display_name;
            hideSearchResults();
            applyLocation(parseFloat(item.lat), parseFloat(item.lon), false);
        });
        box.appendChild(el);
    });

    box.style.display = 'block';
}

function hideSearchResults() {
    const box = document.getElementById('searchResults');
    if (box) box.style.display = 'none';
}

document.addEventListener('click', (e) => {
    if (!e.target.closest('.location-search-wrapper')) hideSearchResults();
});

/* ── Sync lat/lng typed inputs → map marker ─── */
function syncFromInputs() {
    const lat = parseFloat(document.getElementById('inputLatitude').value);
    const lng = parseFloat(document.getElementById('inputLongitude').value);
    if (!isNaN(lat) && !isNaN(lng) && bencanaMap) {
        bencanaMap.setView([lat, lng], 15);
        placeMarker(lat, lng);
    }
}

document.getElementById('inputLatitude').addEventListener('change', syncFromInputs);
document.getElementById('inputLongitude').addEventListener('change', syncFromInputs);

/* ── Modal lifecycle ──────────────────────────── */
const modalEl = document.getElementById('modalTambah');

modalEl.addEventListener('shown.bs.modal', function () {
    const lat = document.getElementById('inputLatitude').value;
    const lng = document.getElementById('inputLongitude').value;
    initMap(lat || null, lng || null);
});

modalEl.addEventListener('hidden.bs.modal', function () {
    stopGPS();
    hideSearchResults();

    const form = document.getElementById('formBencana');
    form.action = '{{ route('admin.bencana.store') }}';
    form.reset();
    document.getElementById('formMethod').value   = 'POST';
    document.getElementById('modalTitle').textContent    = 'Tambah Data Bencana';
    document.getElementById('modalSubtitle').textContent = 'Isi form untuk menambahkan data bencana baru';
    document.getElementById('modalIconWrap').innerHTML   = '<span class="material-symbols-outlined msf ms-sm">add_circle</span>';

    if (bencanaMarker) { bencanaMarker.remove(); bencanaMarker = null; }
    if (bencanaMap)    { bencanaMap.setView(BANDUNG, 12); }
});

/* ── Edit mode ────────────────────────────────── */
function editBencana(id) {
    const b = bencanaData[id];
    if (!b) return;

    const form = document.getElementById('formBencana');
    form.action = `/admin/bencana/${id}`;
    document.getElementById('formMethod').value   = 'PUT';
    document.getElementById('modalTitle').textContent    = 'Edit Data Bencana';
    document.getElementById('modalSubtitle').textContent = 'Perbarui informasi data bencana';
    document.getElementById('modalIconWrap').innerHTML   = '<span class="material-symbols-outlined msf ms-sm">edit</span>';

    form.querySelector('[name="jenis_id"]').value       = b.jenis_id;
    form.querySelector('[name="tingkat_status"]').value = b.tingkat_status;
    document.getElementById('inputLokasi').value        = b.lokasi;
    form.querySelector('[name="tanggal_kejadian"]').value = b.tanggal_kejadian;
    document.getElementById('inputLatitude').value      = b.latitude  || '';
    document.getElementById('inputLongitude').value     = b.longitude || '';
    form.querySelector('[name="deskripsi"]').value      = b.deskripsi || '';

    new bootstrap.Modal(modalEl).show();
}

/* ── Delete confirm ───────────────────────────── */
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
@endsection
