@extends('layouts.app')

@section('title', 'Laporan Saya')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="fw-bold d-flex align-items-center gap-2">
            <span class="material-symbols-outlined text-primary">chat</span>Laporan Saya
        </h3>
        <a href="{{ route('user.laporan.create') }}" class="btn btn-danger d-flex align-items-center gap-2">
            <span class="material-symbols-outlined ms-sm">add_circle</span>Buat Laporan
        </a>
    </div>

    @forelse($laporan as $l)
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1">{{ $l->jenis_bencana }}</h5>
                    <p class="text-muted mb-1 small d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined msf ms-sm text-danger">location_on</span>{{ $l->lokasi_kejadian }}
                    </p>
                    <p class="text-muted mb-0 small d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined ms-sm">schedule</span>{{ $l->created_at->format('d M Y H:i') }}
                    </p>
                </div>
                <div class="d-flex flex-column align-items-end gap-2">
                    {!! $l->status_badge !!}
                    <a href="{{ route('user.laporan.show', $l) }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined ms-sm">visibility</span>Detail
                    </a>
                </div>
            </div>
            @if($l->catatan_petugas)
            <div class="mt-3 p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                <small class="fw-semibold text-muted">Catatan Petugas:</small>
                <p class="mb-0 small">{{ $l->catatan_petugas }}</p>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <span class="material-symbols-outlined ms-xxl text-muted d-block mb-3 opacity-30">inbox</span>
        <h5 class="text-muted">Belum ada laporan</h5>
        <p class="text-muted">Anda belum membuat laporan bencana apapun.</p>
        <a href="{{ route('user.laporan.create') }}" class="btn btn-danger d-inline-flex align-items-center gap-2">
            <span class="material-symbols-outlined ms-sm">add_circle</span>Buat Laporan Pertama
        </a>
    </div>
    @endforelse

    @if($laporan->hasPages())
    <div class="d-flex justify-content-center mt-4">{{ $laporan->links() }}</div>
    @endif
</div>
@endsection
