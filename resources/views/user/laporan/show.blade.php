@extends('layouts.app')

@section('title', 'Detail Laporan #' . $laporan->id)

@push('styles')
<style>
/* ── Progress Tracker ─────────────────────────────── */
.tracker-wrap {
    position: relative;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 0 8px;
}

/* Garis penghubung antar step */
.tracker-line {
    position: absolute;
    top: 22px;
    left: calc(12.5% + 22px);
    right: calc(12.5% + 22px);
    height: 3px;
    background: var(--bs-border-color);
    border-radius: 3px;
    z-index: 0;
    overflow: hidden;
}
.tracker-line-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #3b82f6);
    border-radius: 3px;
    transition: width .6s cubic-bezier(.4,0,.2,1);
}

/* Setiap step */
.tracker-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 25%;
    position: relative;
    z-index: 1;
}

/* Lingkaran step */
.step-circle {
    width: 44px; height: 44px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    transition: all .35s ease;
    position: relative;
}
/* Done */
.step-circle.done {
    background: #10b981;
    color: #fff;
    box-shadow: 0 0 0 4px rgba(16,185,129,.15);
}
/* Active */
.step-circle.active {
    background: #3b82f6;
    color: #fff;
    box-shadow: 0 0 0 6px rgba(59,130,246,.18);
    animation: stepPulse 1.8s ease-in-out infinite;
}
@keyframes stepPulse {
    0%,100%{ box-shadow: 0 0 0 4px rgba(59,130,246,.18); }
    50%    { box-shadow: 0 0 0 9px rgba(59,130,246,.07); }
}
/* Upcoming */
.step-circle.upcoming {
    background: var(--bs-secondary-bg);
    color: var(--bs-secondary-color);
    border: 2px dashed var(--bs-border-color);
}
/* Negative (rejected/hoaks) */
.step-circle.negative {
    background: #ef4444;
    color: #fff;
    box-shadow: 0 0 0 5px rgba(239,68,68,.15);
}

/* Label */
.step-label {
    margin-top: 10px;
    font-size: 12px;
    font-weight: 700;
    text-align: center;
    color: var(--bs-body-color);
}
.step-label.upcoming { color: var(--bs-secondary-color); font-weight: 500; }
.step-sub {
    font-size: 10.5px;
    color: var(--bs-secondary-color);
    text-align: center;
    margin-top: 3px;
    line-height: 1.3;
    max-width: 80px;
}

/* Negative banner */
.negative-banner {
    border-radius: 14px;
    padding: 16px 20px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    border: 1.5px solid;
}
</style>
@endpush

@section('content')
@php
    /* ── Status ke index normal ── */
    $normalSteps = [
        ['key'=>'pending',  'label'=>'Diterima',  'desc'=>'Laporan terkirim',       'icon'=>'inbox'],
        ['key'=>'ditinjau', 'label'=>'Ditinjau',  'desc'=>'Diperiksa petugas',      'icon'=>'manage_search'],
        ['key'=>'diproses', 'label'=>'Diproses',  'desc'=>'Tim turun ke lapangan',  'icon'=>'engineering'],
        ['key'=>'selesai',  'label'=>'Selesai',   'desc'=>'Berhasil ditangani',     'icon'=>'task_alt'],
    ];
    $statusOrder = ['pending'=>0, 'ditinjau'=>1, 'diproses'=>2, 'selesai'=>3];
    $isNegative  = in_array($laporan->status, ['hoaks', 'ditolak']);
    $curIdx      = $statusOrder[$laporan->status] ?? ($isNegative ? 1 : 0);

    /* Persentase garis: jarak antar 4 step = 3 segmen (0/3, 1/3, 2/3, 3/3) */
    $fillPct = $isNegative ? 33 : (int)(($curIdx / 3) * 100);
@endphp

<div class="container py-4" style="max-width:820px;">

    {{-- ── Header ──────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined text-primary ms-lg">description</span>
                Laporan <span class="text-primary">#{{ $laporan->id }}</span>
            </h3>
            <p class="text-muted small mb-0 mt-1">
                Dikirim {{ $laporan->created_at->format('d M Y, H:i') }} WIB
            </p>
        </div>
        <a href="{{ route('user.laporan.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <span class="material-symbols-outlined ms-sm">arrow_back</span>Kembali
        </a>
    </div>

    {{-- ── PROGRESS TRACKER ────────────────────────────────── --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined msf text-primary ms-sm">route</span>
                    Status Laporan
                </h6>
                {!! $laporan->status_badge !!}
            </div>

            {{-- Stepper --}}
            <div class="tracker-wrap">
                {{-- Garis penghubung --}}
                <div class="tracker-line">
                    <div class="tracker-line-fill" style="width:{{ $fillPct }}%;"></div>
                </div>

                @foreach($normalSteps as $i => $step)
                @php
                    if ($isNegative) {
                        $state = $i <= 1 ? 'done' : 'upcoming';
                    } elseif ($i < $curIdx) {
                        $state = 'done';
                    } elseif ($i === $curIdx) {
                        $state = 'active';
                    } else {
                        $state = 'upcoming';
                    }
                @endphp
                <div class="tracker-step">
                    <div class="step-circle {{ $state }}">
                        @if($state === 'done')
                            <span class="material-symbols-outlined msf">check</span>
                        @elseif($state === 'active')
                            <span class="material-symbols-outlined msf">{{ $step['icon'] }}</span>
                        @else
                            <span style="font-size:14px;font-weight:700;">{{ $i + 1 }}</span>
                        @endif
                    </div>
                    <div class="step-label {{ $state === 'upcoming' ? 'upcoming' : '' }}">
                        {{ $step['label'] }}
                    </div>
                    <div class="step-sub">{{ $step['desc'] }}</div>
                    @if($state === 'active')
                    <div class="mt-1">
                        <span style="font-size:9.5px;background:#3b82f6;color:#fff;border-radius:20px;padding:1px 8px;font-weight:600;">SEKARANG</span>
                    </div>
                    @elseif($state === 'done' && $i === 0)
                    <div class="mt-1">
                        <span style="font-size:9.5px;color:var(--bs-secondary-color);">{{ $laporan->created_at->format('d M') }}</span>
                    </div>
                    @elseif($state === 'done' && !$isNegative)
                    <div class="mt-1">
                        <span style="font-size:9.5px;color:var(--bs-secondary-color);">{{ $laporan->updated_at->format('d M') }}</span>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Banner khusus: Ditolak / Hoaks ──────────── --}}
            @if($isNegative)
            <div class="negative-banner mt-4"
                 style="background:rgba(239,68,68,.07);border-color:rgba(239,68,68,.3);">
                <div style="width:42px;height:42px;border-radius:12px;background:#ef4444;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span class="material-symbols-outlined msf text-white ms-sm">
                        {{ $laporan->status === 'hoaks' ? 'block' : 'cancel' }}
                    </span>
                </div>
                <div>
                    <div class="fw-bold" style="color:#ef4444;">
                        {{ $laporan->status === 'hoaks' ? 'Laporan Ditandai Tidak Valid' : 'Laporan Ditolak' }}
                    </div>
                    <div class="small text-muted mt-1">
                        @if($laporan->status === 'hoaks')
                            Setelah ditinjau, petugas menyatakan laporan ini tidak sesuai dengan kondisi di lapangan.
                        @else
                            Laporan ini tidak memenuhi kriteria untuk ditindaklanjuti oleh BPBD.
                        @endif
                        @if($laporan->catatan_petugas)
                            Alasan: <strong>{{ $laporan->catatan_petugas }}</strong>
                        @endif
                    </div>
                </div>
            </div>
            @elseif($laporan->status === 'selesai')
            <div class="negative-banner mt-4"
                 style="background:rgba(16,185,129,.07);border-color:rgba(16,185,129,.3);">
                <div style="width:42px;height:42px;border-radius:12px;background:#10b981;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span class="material-symbols-outlined msf text-white ms-sm">verified</span>
                </div>
                <div>
                    <div class="fw-bold" style="color:#10b981;">Laporan Berhasil Ditangani</div>
                    <div class="small text-muted mt-1">
                        Tim BPBD telah menyelesaikan penanganan laporan ini. Terima kasih atas kontribusi Anda.
                        @if($laporan->catatan_petugas)
                            Catatan: <strong>{{ $laporan->catatan_petugas }}</strong>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Estimasi waktu untuk status aktif --}}
            @if(!$isNegative && $laporan->status !== 'selesai')
            <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2 text-muted small">
                <span class="material-symbols-outlined ms-sm">schedule</span>
                @if($laporan->status === 'pending')
                    Laporan akan segera ditinjau oleh petugas BPBD. Estimasi: <strong class="ms-1">1×24 jam</strong>
                @elseif($laporan->status === 'ditinjau')
                    Petugas sedang memeriksa laporan. Tim akan segera turun ke lokasi jika diperlukan.
                @elseif($laporan->status === 'diproses')
                    Tim BPBD sudah bergerak ke lokasi. Laporan akan segera diselesaikan.
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- ── DETAIL LAPORAN ──────────────────────────────────── --}}
    <div class="row g-4">
        <div class="col-md-8">

            {{-- Informasi utama --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">{{ $laporan->jenis_bencana }}</h5>
                    <p class="text-muted d-flex align-items-center gap-1 mb-1">
                        <span class="material-symbols-outlined msf text-danger ms-sm">location_on</span>
                        {{ $laporan->lokasi_kejadian }}
                    </p>
                    @if($laporan->tanggal_kejadian)
                    <p class="text-muted small d-flex align-items-center gap-1 mb-3">
                        <span class="material-symbols-outlined ms-sm">calendar_month</span>
                        Tanggal kejadian: {{ $laporan->tanggal_kejadian->format('d M Y') }}
                    </p>
                    @endif

                    <hr class="my-3">

                    <h6 class="fw-bold mb-2">Deskripsi</h6>
                    <p class="mb-4">{{ $laporan->deskripsi }}</p>

                    <div class="row g-3">
                        <div class="col-4 text-center">
                            <div class="p-3 rounded-3" style="background:rgba(239,68,68,.08);">
                                <div class="fs-3 fw-black text-danger">{{ $laporan->korban_jiwa }}</div>
                                <small class="text-muted">Korban Jiwa</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="p-3 rounded-3" style="background:rgba(245,158,11,.08);">
                                <div class="fs-3 fw-black text-warning">{{ $laporan->korban_luka }}</div>
                                <small class="text-muted">Korban Luka</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="p-3 rounded-3" style="background:var(--bs-secondary-bg);">
                                <div class="fs-3 fw-black">{{ $laporan->rumah_rusak }}</div>
                                <small class="text-muted">Rumah Rusak</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Foto --}}
            @if($laporan->foto)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined ms-sm text-primary">image</span>Foto Laporan
                    </h6>
                    <img src="{{ Storage::url($laporan->foto) }}"
                         class="img-fluid rounded-3 w-100"
                         style="max-height:360px;object-fit:cover;" alt="Foto Laporan">
                </div>
            </div>
            @endif

            {{-- Komentar petugas --}}
            @if($laporan->komentar->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined ms-sm text-primary">chat</span>
                        Tanggapan Petugas
                        <span class="badge bg-primary rounded-pill">{{ $laporan->komentar->count() }}</span>
                    </h6>
                    @foreach($laporan->komentar as $k)
                    <div class="d-flex gap-3 mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center
                                    justify-content-center flex-shrink-0 fw-bold"
                             style="width:36px;height:36px;font-size:14px;">
                            {{ strtoupper(substr($k->nama_komentator ?? 'P', 0, 1)) }}
                        </div>
                        <div style="min-width:0;flex:1;">
                            <div class="fw-semibold small">{{ $k->nama_komentator ?? 'Petugas BPBD' }}</div>
                            <p class="mb-1 small mt-1">{{ $k->komentar }}</p>
                            <small class="text-muted">{{ $k->created_at->format('d M Y, H:i') }} WIB</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined ms-sm text-primary">info</span>Info Laporan
                    </h6>
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <div class="text-muted small">Tingkat Keparahan</div>
                            @php
                                $kepColor = ['Ringan'=>'success','Sedang'=>'warning','Berat'=>'danger','Sangat Berat'=>'danger'];
                                $kc = $kepColor[$laporan->tingkat_keparahan] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $kc }} mt-1">{{ $laporan->tingkat_keparahan }}</span>
                        </div>
                        <div>
                            <div class="text-muted small">No. Telepon</div>
                            <div class="fw-semibold mt-1">{{ $laporan->telepon ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-muted small">Nomor Laporan</div>
                            <div class="fw-semibold mt-1 font-monospace">#{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>

                    @if($laporan->latitude && $laporan->longitude)
                    <div class="mt-3 pt-3 border-top">
                        <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                            <span class="material-symbols-outlined ms-sm">map</span>Lihat di Google Maps
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            @if($laporan->catatan_petugas && !$isNegative)
            <div class="card border-0 shadow-sm rounded-4"
                 style="border-left:4px solid #3b82f6 !important;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined msf ms-sm text-primary">assignment_turned_in</span>
                        Catatan Petugas
                    </h6>
                    <p class="mb-0 small">{{ $laporan->catatan_petugas }}</p>
                </div>
            </div>
            @endif

            {{-- Bantuan --}}
            <div class="card border-0 rounded-4 mt-4" style="background:var(--bs-secondary-bg);">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined ms-sm text-warning">support_agent</span>
                        Butuh Bantuan?
                    </h6>
                    <p class="small text-muted mb-2">Hubungi BPBD Kota Bandung:</p>
                    <div class="small fw-semibold d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined ms-sm text-success">call</span>(022) 7231929
                    </div>
                    <div class="small text-muted mt-1">Senin – Jumat, 08.00 – 16.00 WIB</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
