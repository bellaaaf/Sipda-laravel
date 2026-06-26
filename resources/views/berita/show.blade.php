@extends('layouts.app')

@section('title', $berita->judul)

@section('content')
<div class="container py-4">
    <a href="{{ route('berita.index') }}" class="btn btn-outline-secondary mb-4 d-inline-flex align-items-center gap-2">
        <span class="material-symbols-outlined ms-sm">arrow_back</span>Kembali
    </a>

    <div class="row g-4">
        <div class="col-md-8">
            <article class="card border-0 shadow-sm rounded-4">
                @if($berita->foto)
                <img src="{{ Storage::url($berita->foto) }}" class="card-img-top rounded-top-4" style="height:350px;object-fit:cover;" alt="{{ $berita->judul }}">
                @endif
                <div class="card-body p-4">
                    @if($berita->bencana)
                    <span class="badge bg-danger rounded-pill mb-2">{{ $berita->bencana->jenis?->nama_bencana }}</span>
                    @endif
                    <h1 class="fw-bold h2 mb-3">{{ $berita->judul }}</h1>
                    <div class="d-flex gap-3 text-muted small mb-4 flex-wrap">
                        <span class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined msf ms-sm">account_circle</span>
                            {{ $berita->admin?->full_name ?? 'BPBD Bandung' }}
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined ms-sm">calendar_month</span>
                            {{ $berita->created_at->format('d F Y') }}
                        </span>
                    </div>
                    <div class="lead">
                        {!! nl2br(e($berita->isi)) !!}
                    </div>
                </div>
            </article>
        </div>

        <div class="col-md-4">
            @if($berita->bencana)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf ms-sm text-danger">warning</span>Bencana Terkait
                    </h6>
                    <span class="badge bg-{{ $berita->bencana->status_color }}">{{ $berita->bencana->tingkat_status }}</span>
                    <p class="mt-2 mb-1 fw-semibold">{{ $berita->bencana->jenis?->nama_bencana }}</p>
                    <p class="text-muted small d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined msf ms-sm">location_on</span>{{ $berita->bencana->lokasi }}
                    </p>
                    <a href="{{ route('bencana.show', $berita->bencana) }}" class="btn btn-outline-danger btn-sm w-100">
                        Lihat Detail Bencana
                    </a>
                </div>
            </div>
            @endif

            @if($related->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Berita Lainnya</h6>
                    @foreach($related as $r)
                    <a href="{{ route('berita.show', $r) }}" class="d-flex gap-2 mb-3 text-decoration-none">
                        @if($r->foto)
                            <img src="{{ Storage::url($r->foto) }}" class="rounded-2 flex-shrink-0" style="width:60px;height:60px;object-fit:cover;">
                        @else
                            <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0 berita-placeholder" style="width:60px;height:60px;background:linear-gradient(135deg,#667eea,#764ba2);">
                                <span class="material-symbols-outlined text-white ms-sm">newspaper</span>
                            </div>
                        @endif
                        <div>
                            <div class="fw-semibold small">{{ Str::limit($r->judul, 60) }}</div>
                            <small class="text-muted">{{ $r->created_at->diffForHumans() }}</small>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
