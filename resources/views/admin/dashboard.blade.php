@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h3 class="fw-bold mb-0 d-flex align-items-center gap-2">
        <span class="material-symbols-outlined text-primary">dashboard</span>Dashboard Admin
    </h3>
    <div class="text-muted small d-flex align-items-center gap-1">
        <span class="material-symbols-outlined ms-sm">calendar_month</span>
        {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

{{-- Welcome Card --}}
<div class="welcome-card welcome-card-admin mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle p-3" style="background: rgba(255,255,255,.2);">
            <span class="material-symbols-outlined msf ms-xl">account_circle</span>
        </div>
        <div>
            <h5 class="fw-bold mb-1">Selamat Datang, {{ auth()->user()->full_name }}!</h5>
            <p class="mb-1 opacity-75 small">Mode Administrator — Akses penuh ke semua fitur SIPDA Bandung</p>
            <small class="opacity-60 d-flex align-items-center gap-1">
                <span class="material-symbols-outlined ms-sm">history</span>
                {{ $stats['laporan_pending'] }} laporan pending perlu diproses
            </small>
        </div>
    </div>
</div>

{{-- Stats Grid --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card" style="border-left-color: #3b82f6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">Total Bencana</div>
                    <div class="fs-2 fw-black">{{ $stats['total_bencana'] }}</div>
                </div>
                <div class="rounded-3 p-2 bg-primary bg-opacity-10 text-primary">
                    <span class="material-symbols-outlined msf">warning</span>
                </div>
            </div>
            <div class="mt-2 small">
                <span class="text-danger me-2 d-inline-flex align-items-center gap-1">
                    <span class="material-symbols-outlined msf" style="font-size:8px;">circle</span>Darurat: {{ $stats['darurat'] }}
                </span>
                <span class="text-primary d-inline-flex align-items-center gap-1">
                    <span class="material-symbols-outlined msf" style="font-size:8px;">circle</span>Siaga: {{ $stats['siaga'] }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.laporan.index') }}" class="text-decoration-none">
        <div class="stat-card" style="border-left-color: #10b981;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">Total Laporan</div>
                    <div class="fs-2 fw-black">{{ $stats['total_laporan'] }}</div>
                </div>
                <div class="rounded-3 p-2 bg-success bg-opacity-10 text-success">
                    <span class="material-symbols-outlined msf">chat</span>
                </div>
            </div>
            @if($trend != 0)
            <div class="mt-2 small text-{{ $trend >= 0 ? 'success' : 'danger' }} d-flex align-items-center gap-1">
                <span class="material-symbols-outlined ms-sm">{{ $trend >= 0 ? 'trending_up' : 'trending_down' }}</span>
                {{ abs($trend) }}% dari bulan lalu
            </div>
            @endif
        </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.laporan.index') }}" class="text-decoration-none">
        <div class="stat-card" style="border-left-color: #f59e0b;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">Laporan Pending</div>
                    <div class="fs-2 fw-black">{{ $stats['laporan_pending'] }}</div>
                </div>
                <div class="rounded-3 p-2 bg-warning bg-opacity-10 text-warning">
                    <span class="material-symbols-outlined msf">pending</span>
                </div>
            </div>
            <div class="mt-2 small text-muted">Butuh segera diproses</div>
        </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
        <div class="stat-card" style="border-left-color: #0ea5e9;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small mb-1">Total User</div>
                    <div class="fs-2 fw-black">{{ $stats['total_user'] }}</div>
                </div>
                <div class="rounded-3 p-2 bg-info bg-opacity-10 text-info">
                    <span class="material-symbols-outlined msf">group</span>
                </div>
            </div>
        </div>
        </a>
    </div>
</div>

{{-- Status Laporan --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label' => 'Diproses', 'key' => 'laporan_diproses', 'color' => 'info',      'icon' => 'autorenew'],
        ['label' => 'Selesai',  'key' => 'laporan_selesai',  'color' => 'success',    'icon' => 'done_all'],
        ['label' => 'Hoaks',    'key' => 'laporan_hoaks',    'color' => 'secondary',  'icon' => 'block'],
    ] as $item)
    <div class="col-md-4">
        <div class="content-card h-100 p-3 d-flex align-items-center gap-3">
            <div class="rounded-3 p-2 bg-{{ $item['color'] }} bg-opacity-10 text-{{ $item['color'] }}">
                <span class="material-symbols-outlined msf">{{ $item['icon'] }}</span>
            </div>
            <div>
                <div class="text-muted small">{{ $item['label'] }}</div>
                <div class="fs-3 fw-black">{{ $stats[$item['key']] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    {{-- Chart --}}
    <div class="col-md-8">
        <div class="content-card h-100 p-4">
            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined ms-sm text-danger">bar_chart</span>Tren Bencana 6 Bulan Terakhir
            </h6>
            <canvas id="bencanaChart" height="120"></canvas>
        </div>
    </div>
    {{-- Status Pie --}}
    <div class="col-md-4">
        <div class="content-card h-100 p-4">
            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined ms-sm text-primary">donut_small</span>Tingkat Bencana
            </h6>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>
</div>

{{-- Log Aktivitas --}}
<div class="content-card p-4">
    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
        <span class="material-symbols-outlined ms-sm text-secondary">history</span>Log Aktivitas Terbaru
    </h6>
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr><th>Waktu</th><th>User</th><th>Aksi</th><th>Deskripsi</th><th>IP</th></tr>
            </thead>
            <tbody>
                @forelse($recentLogs as $log)
                <tr>
                    <td class="text-muted small">{{ $log->created_at->format('d/m H:i') }}</td>
                    <td class="small">{{ $log->user->full_name ?? 'System' }}</td>
                    <td><span class="badge bg-light text-dark">{{ $log->aksi }}</span></td>
                    <td class="small text-muted">{{ Str::limit($log->deskripsi, 60) }}</td>
                    <td class="small text-muted">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada log aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const labels = @json(collect($statistikBulanan)->pluck('bulan'));
const data   = @json(collect($statistikBulanan)->pluck('total'));

new Chart(document.getElementById('bencanaChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Jumlah Bencana',
            data,
            backgroundColor: 'rgba(99, 102, 241, 0.7)',
            borderColor: '#6366f1',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Darurat', 'Siaga', 'Waspada', 'Aman'],
        datasets: [{
            data: [{{ $stats['darurat'] }}, {{ $stats['siaga'] }}, {{ $stats['waspada'] }}, {{ $stats['aman'] }}],
            backgroundColor: ['#dc3545', '#3b82f6', '#f59e0b', '#10b981'],
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
@endpush
