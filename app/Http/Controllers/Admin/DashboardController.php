<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataBencana;
use App\Models\LaporanMasyarakat;
use App\Models\LogAktivitas;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_bencana'    => DataBencana::count(),
            'total_laporan'    => LaporanMasyarakat::count(),
            'laporan_pending'  => LaporanMasyarakat::where('status', 'pending')->count(),
            'laporan_diproses' => LaporanMasyarakat::where('status', 'diproses')->count(),
            'laporan_selesai'  => LaporanMasyarakat::where('status', 'selesai')->count(),
            'laporan_hoaks'    => LaporanMasyarakat::where('status', 'hoaks')->count(),
            'total_user'       => User::whereIn('role', ['masyarakat', 'petugas'])->count(),
            'darurat'          => DataBencana::where('tingkat_status', 'Darurat')->count(),
            'siaga'            => DataBencana::where('tingkat_status', 'Siaga')->count(),
            'waspada'          => DataBencana::where('tingkat_status', 'Waspada')->count(),
            'aman'             => DataBencana::where('tingkat_status', 'Aman')->count(),
        ];

        $bulanIni  = Carbon::now()->format('Y-m');
        $bulanLalu = Carbon::now()->subMonth()->format('Y-m');
        $laporanBulanIni  = LaporanMasyarakat::whereRaw("DATE_FORMAT(created_at,'%Y-%m') = ?", [$bulanIni])->count();
        $laporanBulanLalu = LaporanMasyarakat::whereRaw("DATE_FORMAT(created_at,'%Y-%m') = ?", [$bulanLalu])->count();
        $trend = $laporanBulanLalu > 0
            ? round(($laporanBulanIni - $laporanBulanLalu) / $laporanBulanLalu * 100)
            : 0;

        $statistikBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan     = Carbon::now()->subMonths($i)->format('Y-m');
            $namaBulan = Carbon::now()->subMonths($i)->translatedFormat('M Y');
            $total     = DataBencana::whereRaw("DATE_FORMAT(tanggal_kejadian,'%Y-%m') = ?", [$bulan])->count();
            $statistikBulanan[] = ['bulan' => $namaBulan, 'total' => $total];
        }

        $recentLogs = LogAktivitas::with('user')->orderByDesc('created_at')->take(10)->get();

        return view('admin.dashboard', compact('stats', 'trend', 'laporanBulanIni', 'statistikBulanan', 'recentLogs'));
    }
}
