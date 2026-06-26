@extends('layouts.admin')

@section('title', 'Kelola Berita')

@push('styles')
<style>
/* Filter tabs */
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

/* Berita card */
.berita-card {
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--bs-border-color);
    transition: transform 0.2s, box-shadow 0.2s;
}
.berita-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12) !important;
}
.berita-thumb {
    width: 100%;
    height: 160px;
    object-fit: cover;
}
.berita-thumb-placeholder {
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.berita-status-badge {
    position: absolute;
    top: 10px;
    right: 10px;
}
.berita-body { padding: 1rem 1.1rem 1.1rem; }
.berita-meta {
    font-size: 0.72rem;
    color: var(--bs-secondary-color);
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 8px;
}
.berita-meta span { display: flex; align-items: center; gap: 3px; }
.berita-judul {
    font-weight: 700;
    font-size: 0.92rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 0.6rem;
}
.berita-actions {
    display: flex;
    gap: 6px;
    padding: 0.6rem 1.1rem 0.8rem;
    border-top: 1px solid var(--bs-border-color);
}
</style>
@endpush

@section('content')

{{-- ── Header ────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-primary ms-lg">newspaper</span>Kelola Berita
        </h3>
        <p class="text-muted mb-0 small">Total {{ $berita->count() }} berita</p>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <div class="input-group" style="width:210px;">
            <span class="input-group-text bg-transparent">
                <span class="material-symbols-outlined ms-sm text-muted">search</span>
            </span>
            <input type="text" class="form-control" id="searchInput" placeholder="Cari judul berita…">
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahBerita">
            <span class="material-symbols-outlined ms-sm">add_circle</span>Buat Berita
        </button>
    </div>
</div>

{{-- ── Status Tabs ───────────────────────────────────────── --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    @php
    $tabs = [
        ['value'=>'',          'label'=>'Semua',          'color'=>'#4f46e5', 'icon'=>'inbox'],
        ['value'=>'published', 'label'=>'Dipublikasikan', 'color'=>'#10b981', 'icon'=>'published_with_changes'],
        ['value'=>'draft',     'label'=>'Draft',          'color'=>'#94a3b8', 'icon'=>'edit_note'],
    ];
    @endphp
    @foreach($tabs as $tab)
    @php $count = $tab['value'] === '' ? $berita->count() : ($statusCounts[$tab['value']] ?? 0); @endphp
    <button type="button"
            class="status-tab {{ $tab['value'] === '' ? 'active' : '' }}"
            data-filter="{{ $tab['value'] }}"
            data-color="{{ $tab['color'] }}"
            style="{{ $tab['value'] === '' ? 'background:'.$tab['color'].';' : '' }}">
        <span class="material-symbols-outlined msf" style="font-size:14px;">{{ $tab['icon'] }}</span>
        {{ $tab['label'] }}
        <span class="tab-count">{{ $count }}</span>
    </button>
    @endforeach
</div>

{{-- ── Card Grid ─────────────────────────────────────────── --}}
<div class="row g-4" id="beritaGrid">
    @forelse($berita as $b)
    <div class="col-sm-6 col-lg-4 berita-item" data-status="{{ $b->status }}">
        <div class="berita-card shadow-sm h-100 d-flex flex-column">
            {{-- Thumbnail --}}
            <div class="position-relative">
                @if($b->foto)
                    <img src="{{ Storage::url($b->foto) }}" class="berita-thumb" alt="{{ $b->judul }}">
                @else
                    <div class="berita-thumb-placeholder"
                         style="background:linear-gradient(135deg,{{ $b->status==='published' ? '#0f3460,#1a6fc4' : '#374151,#6b7280' }});">
                        <span class="material-symbols-outlined text-white opacity-20" style="font-size:52px;">newspaper</span>
                    </div>
                @endif
                {{-- Status overlay badge --}}
                <span class="berita-status-badge badge d-inline-flex align-items-center gap-1
                             {{ $b->status === 'published' ? 'bg-success' : 'bg-secondary' }}"
                      style="font-size:10px;">
                    <span class="material-symbols-outlined msf" style="font-size:11px;">
                        {{ $b->status === 'published' ? 'published_with_changes' : 'edit_note' }}
                    </span>
                    {{ $b->status === 'published' ? 'Dipublikasikan' : 'Draft' }}
                </span>
            </div>

            {{-- Body --}}
            <div class="berita-body flex-grow-1">
                {{-- Meta --}}
                <div class="berita-meta">
                    <span>
                        <span class="material-symbols-outlined msf" style="font-size:11px;">person</span>
                        {{ $b->admin?->full_name ?? 'Admin' }}
                    </span>
                    <span>
                        <span class="material-symbols-outlined msf" style="font-size:11px;">calendar_today</span>
                        {{ $b->created_at->format('d M Y') }}
                    </span>
                    @if($b->bencana)
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill" style="font-size:10px;">
                        {{ $b->bencana->jenis?->nama_bencana ?? 'Bencana' }}
                    </span>
                    @else
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill" style="font-size:10px;">Umum</span>
                    @endif
                </div>

                {{-- Judul --}}
                <div class="berita-judul">{{ $b->judul }}</div>
            </div>

            {{-- Actions --}}
            <div class="berita-actions">
                <a href="{{ route('berita.show', $b) }}" target="_blank"
                   class="btn btn-sm btn-outline-info flex-grow-1 d-flex align-items-center justify-content-center gap-1">
                    <span class="material-symbols-outlined ms-sm">open_in_new</span>Lihat
                </a>
                <form action="{{ route('admin.berita.destroy', $b) }}" method="POST" class="d-inline flex-grow-1">
                    @csrf @method('DELETE')
                    <button type="button"
                            class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-1"
                            onclick="confirmDelete(this.closest('form'), 'Hapus Berita', '{{ addslashes(Str::limit($b->judul, 40)) }} akan dihapus permanen.')">
                        <span class="material-symbols-outlined ms-sm">delete</span>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12" id="emptyState">
        <div class="text-center py-5">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                 style="width:80px;height:80px;background:var(--bs-tertiary-bg);">
                <span class="material-symbols-outlined ms-xxl text-muted opacity-40">newspaper</span>
            </div>
            <div class="fw-semibold mb-1">Belum ada berita</div>
            <div class="text-muted small mb-3">Mulai buat berita untuk dipublikasikan.</div>
            <button class="btn btn-primary d-inline-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#modalTambahBerita">
                <span class="material-symbols-outlined ms-sm">add_circle</span>Buat Berita Pertama
            </button>
        </div>
    </div>
    @endforelse
</div>

{{-- Empty search state --}}
<div id="noResults" class="text-center py-5 d-none">
    <span class="material-symbols-outlined ms-xxl text-muted opacity-30 d-block mb-2">search_off</span>
    <div class="fw-semibold">Tidak ada hasil</div>
    <div class="text-muted small">Coba kata kunci lain.</div>
</div>

{{-- ── Modal Buat Berita ─────────────────────────────────── --}}
<div class="modal fade" id="modalTambahBerita" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <div class="modal-icon-wrap me-3">
                    <span class="material-symbols-outlined msf ms-sm">newspaper</span>
                </div>
                <div class="flex-grow-1">
                    <h5 class="modal-title mb-0">Buat Berita</h5>
                    <div class="modal-subtitle">Isi form untuk mempublikasikan berita baru</div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-0">
                        <label class="form-label">Bencana Terkait <span class="text-muted fw-normal" style="text-transform:none;">(opsional)</span></label>
                        <select name="bencana_id" class="form-select">
                            <option value="">— Tidak Terkait —</option>
                            @foreach($bencana as $bc)
                            <option value="{{ $bc->id }}">{{ $bc->jenis?->nama_bencana }} — {{ Str::limit($bc->lokasi, 40) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-label">
                            <span class="material-symbols-outlined ms-sm">edit_note</span>Konten Berita
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Judul Berita <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" placeholder="Masukkan judul berita yang menarik…" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Isi Berita <span class="text-danger">*</span></label>
                            <textarea name="isi" class="form-control" rows="7" placeholder="Tulis isi berita di sini…" required></textarea>
                        </div>
                    </div>

                    <div class="modal-section">
                        <div class="modal-section-label">
                            <span class="material-symbols-outlined ms-sm">tune</span>Pengaturan Publikasi
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="published">Langsung Publikasi</option>
                                    <option value="draft">Simpan sebagai Draft</option>
                                </select>
                            </div>
                            <div class="col-md-6">
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
                        <span class="material-symbols-outlined msf ms-sm">publish</span>Publikasikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const items = Array.from(document.querySelectorAll('.berita-item'));
    const noResults = document.getElementById('noResults');
    let activeFilter = '';
    let searchQuery  = '';

    function applyFilters() {
        let visible = 0;
        items.forEach(item => {
            const matchStatus = !activeFilter || item.dataset.status === activeFilter;
            const matchSearch = !searchQuery  || item.textContent.toLowerCase().includes(searchQuery);
            const show = matchStatus && matchSearch;
            item.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        noResults.classList.toggle('d-none', visible > 0);
    }

    document.querySelectorAll('.status-tab').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.status-tab').forEach(b => {
                b.classList.remove('active');
                b.style.background = '';
            });
            this.classList.add('active');
            this.style.background = this.dataset.color;
            activeFilter = this.dataset.filter;
            applyFilters();
        });
    });

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
