@extends('layouts.app')

@section('title', 'Detail Laporan #' . $laporan->id)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="fw-bold d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-primary">description</span>Detail Laporan #{{ $laporan->id }}
        </h3>
        <a href="{{ route('user.laporan.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <span class="material-symbols-outlined ms-sm">arrow_back</span>Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                        <h4 class="fw-bold">{{ $laporan->jenis_bencana }}</h4>
                        {!! $laporan->status_badge !!}
                    </div>
                    <p class="text-muted d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined msf ms-sm text-danger">location_on</span>{{ $laporan->lokasi_kejadian }}
                    </p>
                    <p class="text-muted small d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined ms-sm">calendar_month</span>
                        Dilaporkan: {{ $laporan->created_at->format('d M Y H:i') }}
                    </p>

                    <hr>

                    <h6 class="fw-bold">Deskripsi</h6>
                    <p>{{ $laporan->deskripsi }}</p>

                    <div class="row g-3 mt-2">
                        <div class="col-4 text-center">
                            <div class="p-3 bg-danger bg-opacity-10 rounded-3">
                                <div class="fs-3 fw-black text-danger">{{ $laporan->korban_jiwa }}</div>
                                <small class="text-muted">Korban Jiwa</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="p-3 bg-warning bg-opacity-10 rounded-3">
                                <div class="fs-3 fw-black text-warning">{{ $laporan->korban_luka }}</div>
                                <small class="text-muted">Korban Luka</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                                <div class="fs-3 fw-black text-secondary">{{ $laporan->rumah_rusak }}</div>
                                <small class="text-muted">Rumah Rusak</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($laporan->foto)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined ms-sm">image</span>Foto Laporan
                    </h6>
                    <img src="{{ Storage::url($laporan->foto) }}" class="img-fluid rounded-3 w-100" style="max-height:350px;object-fit:cover;" alt="Foto">
                </div>
            </div>
            @endif

            @if($laporan->komentar->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined ms-sm">chat</span>Tanggapan Petugas
                    </h6>
                    @foreach($laporan->komentar as $k)
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;font-size:14px;">
                            {{ strtoupper(substr($k->nama_komentator, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold small">{{ $k->nama_komentator }}</div>
                            <p class="mb-1 small">{{ $k->komentar }}</p>
                            <small class="text-muted">{{ $k->created_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Info Laporan</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><span class="text-muted">Keparahan:</span><br><strong>{{ $laporan->tingkat_keparahan }}</strong></li>
                        <li class="mb-2"><span class="text-muted">Telepon:</span><br><strong>{{ $laporan->telepon ?? '-' }}</strong></li>
                        @if($laporan->tanggal_kejadian)
                        <li class="mb-2"><span class="text-muted">Tgl Kejadian:</span><br><strong>{{ $laporan->tanggal_kejadian->format('d M Y') }}</strong></li>
                        @endif
                    </ul>
                    @if($laporan->latitude && $laporan->longitude)
                    <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                        <span class="material-symbols-outlined ms-sm">map</span>Lihat Lokasi di Maps
                    </a>
                    @endif
                </div>
            </div>

            @if($laporan->catatan_petugas)
            <div class="card border-0 shadow-sm rounded-4 border-start border-4 border-info">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf ms-sm text-info">assignment_turned_in</span>Catatan Petugas
                    </h6>
                    <p class="mb-0 small">{{ $laporan->catatan_petugas }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
