@extends('layouts.admin')

@section('title', 'Detail Laporan #' . $laporan->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <span class="material-symbols-outlined text-primary">description</span>Detail Laporan #{{ $laporan->id }}
    </h3>
    <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <span class="material-symbols-outlined ms-sm">arrow_back</span>Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="content-card p-4 mb-4">
            <h5 class="fw-bold mb-3">Informasi Laporan</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                        <small class="text-muted d-block">Nama Pelapor</small>
                        <strong>{{ $laporan->nama_pelapor }}</strong>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                        <small class="text-muted d-block">Email</small>
                        <strong>{{ $laporan->email_pelapor }}</strong>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                        <small class="text-muted d-block">Jenis Bencana</small>
                        <strong>{{ $laporan->jenis_bencana }}</strong>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                        <small class="text-muted d-block">Tingkat Keparahan</small>
                        <strong>{{ $laporan->tingkat_keparahan }}</strong>
                    </div>
                </div>
                <div class="col-12">
                    <div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                        <small class="text-muted d-block">Lokasi Kejadian</small>
                        <strong>{{ $laporan->lokasi_kejadian }}</strong>
                    </div>
                </div>
                <div class="col-12">
                    <div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                        <small class="text-muted d-block">Deskripsi</small>
                        <p class="mb-0">{{ $laporan->deskripsi }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 text-center bg-danger bg-opacity-10">
                        <small class="text-muted d-block">Korban Jiwa</small>
                        <strong class="fs-4 text-danger">{{ $laporan->korban_jiwa }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 text-center bg-warning bg-opacity-10">
                        <small class="text-muted d-block">Korban Luka</small>
                        <strong class="fs-4 text-warning">{{ $laporan->korban_luka }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 text-center" style="background:var(--bs-secondary-bg);">
                        <small class="text-muted d-block">Rumah Rusak</small>
                        <strong class="fs-4 text-secondary">{{ $laporan->rumah_rusak }}</strong>
                    </div>
                </div>
            </div>
        </div>

        @if($laporan->foto)
        <div class="content-card p-4 mb-4">
            <h5 class="fw-bold mb-3">Foto Laporan</h5>
            <img src="{{ Storage::url($laporan->foto) }}" class="img-fluid rounded-3" style="max-height:350px;object-fit:cover;" alt="Foto Laporan">
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="content-card p-4 mb-4">
            <h5 class="fw-bold mb-3">Status &amp; Tindakan</h5>
            <div class="mb-3 text-center">{!! $laporan->status_badge !!}</div>
            <form action="{{ route('admin.laporan.status', $laporan) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Ubah Status</label>
                    <select name="status" class="form-select">
                        @foreach(['pending','diproses','selesai','hoaks','ditolak','ditinjau'] as $s)
                        <option value="{{ $s }}" {{ $laporan->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Catatan Petugas</label>
                    <textarea name="catatan_petugas" class="form-control" rows="3" placeholder="Catatan opsional...">{{ $laporan->catatan_petugas }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                    <span class="material-symbols-outlined ms-sm">save</span>Simpan
                </button>
            </form>
        </div>

        <div class="content-card p-4">
            <h5 class="fw-bold mb-3">Informasi Pelapor</h5>
            <ul class="list-unstyled small">
                <li class="mb-2 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined ms-sm text-muted">calendar_month</span>
                    {{ $laporan->created_at->format('d M Y H:i') }}
                </li>
                <li class="mb-2 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined ms-sm text-muted">phone</span>
                    {{ $laporan->telepon ?? '-' }}
                </li>
                <li class="mb-2 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined ms-sm text-muted">language</span>
                    {{ $laporan->ip_address }}
                </li>
                @if($laporan->latitude && $laporan->longitude)
                <li>
                    <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                        <span class="material-symbols-outlined ms-sm">map</span>Lihat Peta
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection
