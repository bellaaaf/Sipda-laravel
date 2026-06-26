@extends('layouts.app')

@section('title', 'Detail Bencana')

@section('content')
<div class="container py-4">
    <a href="{{ route('bencana.index') }}" class="btn btn-outline-secondary mb-4 d-inline-flex align-items-center gap-2">
        <span class="material-symbols-outlined ms-sm">arrow_back</span>Kembali
    </a>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                        <h3 class="fw-bold mb-0">{{ $bencana->jenis?->nama_bencana ?? 'Bencana Alam' }}</h3>
                        <span class="badge bg-{{ $bencana->status_color }} fs-6 px-3 py-2 rounded-pill">{{ $bencana->tingkat_status }}</span>
                    </div>
                    <p class="text-muted d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined msf ms-sm text-danger">location_on</span>{{ $bencana->lokasi }}
                    </p>
                    <p class="text-muted small d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined ms-sm">calendar_month</span>{{ $bencana->tanggal_kejadian->format('d F Y') }}
                    </p>
                    <hr>
                    <h5 class="fw-bold">Deskripsi</h5>
                    <p>{{ $bencana->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
                </div>
            </div>

            @if($bencana->latitude && $bencana->longitude)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined ms-sm">map</span>Peta Lokasi
                    </h5>
                    <div id="detailMap" style="height:300px;border-radius:.75rem;"></div>
                    <div class="mt-3 d-flex gap-2">
                        <a href="https://www.google.com/maps?q={{ $bencana->latitude }},{{ $bencana->longitude }}" target="_blank"
                            class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined ms-sm">map</span>Google Maps
                        </a>
                        <a href="https://www.google.com/maps/dir//{{ $bencana->latitude }},{{ $bencana->longitude }}" target="_blank"
                            class="btn btn-sm btn-outline-success d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined ms-sm">navigation</span>Petunjuk Arah
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if($bencana->updates->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined ms-sm">history</span>Riwayat Update
                    </h5>
                    @foreach($bencana->updates as $upd)
                    <div class="d-flex gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                            style="width:36px;height:36px;background: {{ ['Darurat'=>'#dc3545','Siaga'=>'#3b82f6','Waspada'=>'#f59e0b','Aman'=>'#10b981'][$upd->status] ?? '#6c757d' }};">
                            <span class="material-symbols-outlined msf" style="font-size:16px;">flag</span>
                        </div>
                        <div>
                            <span class="badge bg-{{ ['Darurat'=>'danger','Siaga'=>'primary','Waspada'=>'warning','Aman'=>'success'][$upd->status] ?? 'secondary' }}">{{ $upd->status }}</span>
                            <p class="mb-1 small mt-1">{{ $upd->deskripsi }}</p>
                            <small class="text-muted">{{ $upd->created_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            @if($bencana->berita->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf ms-sm text-primary">newspaper</span>Berita Terkait
                    </h5>
                    @foreach($bencana->berita as $b)
                    <div class="mb-3 pb-3 border-bottom">
                        <a href="{{ route('berita.show', $b) }}" class="text-decoration-none">
                            <div class="fw-semibold small">{{ Str::limit($b->judul, 60) }}</div>
                        </a>
                        <small class="text-muted">{{ $b->created_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Laporkan Kejadian di Sini?</h6>
                    <p class="text-muted small">Jika Anda menyaksikan kejadian bencana, segera laporkan agar petugas dapat merespons lebih cepat.</p>
                    @auth
                        @if(auth()->user()->isMasyarakat())
                        <a href="{{ route('user.laporan.create') }}" class="btn btn-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                            <span class="material-symbols-outlined ms-sm">campaign</span>Buat Laporan
                        </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                            <span class="material-symbols-outlined ms-sm">login</span>Login untuk Melapor
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($bencana->latitude && $bencana->longitude)
<script>
const map = L.map('detailMap').setView([{{ $bencana->latitude }}, {{ $bencana->longitude }}], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);
L.marker([{{ $bencana->latitude }}, {{ $bencana->longitude }}])
    .addTo(map)
    .bindPopup('<strong>{{ addslashes($bencana->jenis?->nama_bencana ?? "Bencana") }}</strong><br>{{ addslashes($bencana->lokasi) }}')
    .openPopup();
</script>
@endif
@endpush
