@extends('layouts.app')

@section('title', 'Berita Terkini')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-1 d-flex align-items-center gap-2">
        <span class="material-symbols-outlined msf text-primary ms-lg">newspaper</span>Berita Terkini
    </h2>
    <p class="text-muted mb-4">Informasi resmi dari BPBD Kota Bandung</p>

    <div class="row g-4">
        @forelse($berita as $b)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                @if($b->foto)
                    <img src="{{ Storage::url($b->foto) }}" class="card-img-top rounded-top-4" style="height:200px;object-fit:cover;" alt="{{ $b->judul }}">
                @else
                    <div class="rounded-top-4 d-flex align-items-center justify-content-center berita-placeholder" style="height:180px;background:linear-gradient(135deg,#667eea,#764ba2);">
                        <span class="material-symbols-outlined text-white ms-xxl opacity-50">newspaper</span>
                    </div>
                @endif
                <div class="card-body">
                    @if($b->bencana)
                    <span class="badge bg-danger rounded-pill mb-2">{{ $b->bencana->jenis?->nama_bencana }}</span>
                    @endif
                    <h5 class="fw-bold">{{ Str::limit($b->judul, 70) }}</h5>
                    <p class="text-muted small mb-0">{{ Str::limit(strip_tags($b->isi), 120) }}</p>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <small class="text-muted d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined ms-sm">schedule</span>{{ $b->created_at->diffForHumans() }}
                    </small>
                    <a href="{{ route('berita.show', $b) }}" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">
            <span class="material-symbols-outlined ms-xxl d-block mb-3 opacity-30">inbox</span>
            <h4>Belum Ada Berita</h4>
            <p>Berita dari BPBD Bandung akan segera hadir.</p>
        </div>
        @endforelse
    </div>

    @if($berita->hasPages())
    <div class="d-flex justify-content-center mt-4">{{ $berita->links() }}</div>
    @endif
</div>
@endsection
