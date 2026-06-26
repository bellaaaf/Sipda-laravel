<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\ArsipHoaks;
use App\Models\LaporanMasyarakat;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending'  => LaporanMasyarakat::where('status', 'pending')->count(),
            'diproses' => LaporanMasyarakat::where('status', 'diproses')->count(),
            'selesai'  => LaporanMasyarakat::where('status', 'selesai')->count(),
            'hoaks'    => LaporanMasyarakat::where('status', 'hoaks')->count(),
            'total'    => LaporanMasyarakat::count(),
        ];

        $laporanTerbaru = LaporanMasyarakat::with('user')
            ->whereIn('status', ['pending', 'diproses'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $arsipHoaksTerbaru = ArsipHoaks::with(['laporan', 'petugas'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact('stats', 'laporanTerbaru', 'arsipHoaksTerbaru'));
    }
}
