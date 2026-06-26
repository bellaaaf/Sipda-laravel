<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\DataBencana;
use App\Models\LaporanMasyarakat;

class HomeController extends Controller
{
    public function index()
    {
        $bencanaAktif = DataBencana::with('jenis')
            ->whereIn('tingkat_status', ['Darurat', 'Siaga', 'Waspada'])
            ->orderByRaw("FIELD(tingkat_status,'Darurat','Siaga','Waspada')")
            ->orderByDesc('tanggal_kejadian')
            ->take(6)
            ->get();

        $beritaTerbaru = Berita::with(['bencana.jenis', 'admin'])
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        $statistik = [
            'total_bencana'   => DataBencana::count(),
            'bencana_darurat' => DataBencana::where('tingkat_status', 'Darurat')->count(),
            'total_laporan'   => LaporanMasyarakat::count(),
            'laporan_pending' => LaporanMasyarakat::where('status', 'pending')->count(),
        ];

        return view('home', compact('bencanaAktif', 'beritaTerbaru', 'statistik'));
    }

    public function layanan()
    {
        return view('layanan');
    }
}
