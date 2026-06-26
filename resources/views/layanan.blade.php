@extends('layouts.app')

@section('title', 'Layanan')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold d-flex align-items-center justify-content-center gap-2">
            <span class="material-symbols-outlined msf text-primary ms-lg">grid_view</span>Layanan SIPDA Bandung
        </h2>
        <p class="text-muted fs-5">Sistem terintegrasi untuk keselamatan warga Kota Bandung</p>
    </div>

    <div class="row g-4">
        @foreach([
            ['icon' => 'warning',       'color' => 'danger',    'title' => 'Monitoring Bencana',   'desc' => 'Pantau kejadian bencana alam secara real-time dengan peta interaktif. Data terintegrasi dari BMKG dan BPBD.'],
            ['icon' => 'campaign',      'color' => 'warning',   'title' => 'Pelaporan Bencana',    'desc' => 'Laporkan kejadian bencana di sekitar Anda. Laporan akan segera ditinjau oleh petugas BPBD yang berpengalaman.'],
            ['icon' => 'fact_check',    'color' => 'success',   'title' => 'Verifikasi Laporan',   'desc' => 'Setiap laporan masyarakat diverifikasi secara ketat oleh tim petugas BPBD untuk memastikan keakuratan informasi.'],
            ['icon' => 'newspaper',     'color' => 'primary',   'title' => 'Berita & Informasi',   'desc' => 'Dapatkan berita resmi dan informasi terkini seputar penanggulangan bencana dari BPBD Kota Bandung.'],
            ['icon' => 'notifications', 'color' => 'info',      'title' => 'Notifikasi Real-time', 'desc' => 'Terima notifikasi langsung saat status laporan Anda diperbarui oleh petugas BPBD.'],
            ['icon' => 'badge',         'color' => 'secondary', 'title' => 'Tim Profesional',      'desc' => 'Ditangani oleh tim petugas BPBD Kota Bandung yang terlatih dan berpengalaman dalam manajemen bencana.'],
        ] as $layanan)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4">
                <div class="layanan-icon bg-{{ $layanan['color'] }} bg-opacity-10 text-{{ $layanan['color'] }} mx-auto">
                    <span class="material-symbols-outlined msf ms-lg">{{ $layanan['icon'] }}</span>
                </div>
                <h5 class="fw-bold">{{ $layanan['title'] }}</h5>
                <p class="text-muted small mb-0">{{ $layanan['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-5 p-5 rounded-4 text-white text-center cta-section">
        <h4 class="fw-bold mb-3">Siap Bergabung?</h4>
        <p class="mb-4 opacity-75">Daftar sekarang dan mulai berkontribusi untuk keselamatan Kota Bandung.</p>
        @guest
        <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold px-5 d-inline-flex align-items-center gap-2">
            <span class="material-symbols-outlined msf">person_add</span>Daftar Gratis
        </a>
        @else
        <a href="{{ route('user.laporan.create') }}" class="btn btn-warning btn-lg fw-bold px-5 d-inline-flex align-items-center gap-2">
            <span class="material-symbols-outlined msf">campaign</span>Buat Laporan Sekarang
        </a>
        @endguest
    </div>
</div>
@endsection
