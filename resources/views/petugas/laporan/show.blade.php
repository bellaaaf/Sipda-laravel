@extends('layouts.petugas')

@section('title', 'Tinjau Laporan #' . $laporan->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <span class="material-symbols-outlined text-primary">fact_check</span>Tinjau Laporan #{{ $laporan->id }}
    </h3>
    <a href="{{ route('petugas.laporan.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <span class="material-symbols-outlined ms-sm">arrow_back</span>Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="content-card p-4 mb-4">
            <h5 class="fw-bold mb-3">Detail Laporan</h5>
            <div class="row g-3">
                @foreach([
                    ['Nama Pelapor', $laporan->nama_pelapor],
                    ['Email', $laporan->email_pelapor],
                    ['Telepon', $laporan->telepon ?? '-'],
                    ['Jenis Bencana', $laporan->jenis_bencana],
                    ['Tingkat Keparahan', $laporan->tingkat_keparahan],
                    ['Tanggal Kejadian', $laporan->tanggal_kejadian?->format('d M Y') ?? '-'],
                ] as [$label, $value])
                <div class="col-md-6">
                    <div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                        <small class="text-muted d-block">{{ $label }}</small>
                        <strong>{{ $value }}</strong>
                    </div>
                </div>
                @endforeach
                <div class="col-12">
                    <div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                        <small class="text-muted d-block">Lokasi Kejadian</small>
                        <strong>{{ $laporan->lokasi_kejadian }}</strong>
                        @if($laporan->latitude && $laporan->longitude)
                        <br>
                        <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2 d-inline-flex align-items-center gap-2">
                            <span class="material-symbols-outlined ms-sm">map</span>Lihat Peta
                        </a>
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    <div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                        <small class="text-muted d-block">Deskripsi</small>
                        <p class="mb-0">{{ $laporan->deskripsi }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($laporan->foto)
        <div class="content-card p-4 mb-4">
            <h5 class="fw-bold mb-3">Foto Laporan</h5>
            <img src="{{ Storage::url($laporan->foto) }}" class="img-fluid rounded-3" style="max-height:300px;object-fit:cover;" alt="Foto">
        </div>
        @endif

        <div class="content-card p-4">
            <h5 class="fw-bold mb-3">Statistik Korban</h5>
            <div class="row g-3 text-center">
                <div class="col-4"><div class="p-3 bg-danger bg-opacity-10 rounded-3"><div class="fs-3 fw-black text-danger">{{ $laporan->korban_jiwa }}</div><small class="text-muted">Korban Jiwa</small></div></div>
                <div class="col-4"><div class="p-3 bg-warning bg-opacity-10 rounded-3"><div class="fs-3 fw-black text-warning">{{ $laporan->korban_luka }}</div><small class="text-muted">Korban Luka</small></div></div>
                <div class="col-4"><div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);"><div class="fs-3 fw-black text-secondary">{{ $laporan->rumah_rusak }}</div><small class="text-muted">Rumah Rusak</small></div></div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="content-card p-4 mb-4">
            <h5 class="fw-bold mb-3">Tindakan</h5>
            <div class="text-center mb-3">{!! $laporan->status_badge !!}</div>

            <form action="{{ route('petugas.laporan.tinjau', $laporan) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Ubah Status</label>
                    <select name="status" class="form-select" id="statusSelect" onchange="toggleHoaks()">
                        @foreach(['diproses','selesai','hoaks','ditolak','ditinjau'] as $s)
                        <option value="{{ $s }}" {{ $laporan->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3" id="alasanHoaks" style="display:none;">
                    <label class="form-label fw-semibold small text-danger">Alasan Hoaks <span class="text-danger">*</span></label>
                    <textarea name="alasan_hoaks" class="form-control" rows="3" placeholder="Jelaskan mengapa laporan ini hoaks..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Komentar (opsional)</label>
                    <textarea name="komentar" class="form-control" rows="2" placeholder="Tambahkan komentar petugas..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Catatan Petugas</label>
                    <textarea name="catatan_petugas" class="form-control" rows="3" placeholder="Catatan internal...">{{ $laporan->catatan_petugas }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                    <span class="material-symbols-outlined ms-sm">save</span>Simpan Tinjauan
                </button>
            </form>
        </div>

        @if($laporan->komentar->isNotEmpty())
        <div class="content-card p-4">
            <h6 class="fw-bold mb-3">Riwayat Komentar</h6>
            @foreach($laporan->komentar as $k)
            <div class="mb-3 pb-3 border-bottom">
                <div class="fw-semibold small">{{ $k->nama_komentator }}</div>
                <p class="mb-1 small">{{ $k->komentar }}</p>
                <small class="text-muted">{{ $k->created_at->format('d/m/Y H:i') }}</small>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleHoaks() {
    const val = document.getElementById('statusSelect').value;
    document.getElementById('alasanHoaks').style.display = val === 'hoaks' ? 'block' : 'none';
}
toggleHoaks();
</script>
@endpush
@endsection
