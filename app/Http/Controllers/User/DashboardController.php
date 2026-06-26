<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\DataBencana;
use App\Models\LaporanMasyarakat;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $laporanSaya = LaporanMasyarakat::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $bencanaAktif = DataBencana::with('jenis')
            ->whereIn('tingkat_status', ['Darurat', 'Siaga'])
            ->orderByDesc('tanggal_kejadian')
            ->take(3)
            ->get();

        $beritaTerbaru = Berita::where('status', 'published')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        $statistikLaporan = [
            'total'    => LaporanMasyarakat::where('user_id', $user->id)->count(),
            'pending'  => LaporanMasyarakat::where('user_id', $user->id)->where('status', 'pending')->count(),
            'diproses' => LaporanMasyarakat::where('user_id', $user->id)->where('status', 'diproses')->count(),
            'selesai'  => LaporanMasyarakat::where('user_id', $user->id)->where('status', 'selesai')->count(),
        ];

        return view('user.dashboard', compact('laporanSaya', 'notifikasi', 'bencanaAktif', 'beritaTerbaru', 'statistikLaporan'));
    }
}
