<?php

namespace App\Http\Controllers;

use App\Models\DataBencana;
use App\Models\JenisBencana;

class PublicBencanaController extends Controller
{
    public function index()
    {
        $bencana = DataBencana::with('jenis')
            ->orderByRaw("FIELD(tingkat_status,'Darurat','Siaga','Waspada','Aman')")
            ->orderByDesc('tanggal_kejadian')
            ->paginate(12);

        $jenis = JenisBencana::all();

        $mapData = DataBencana::with('jenis')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'jenis_id', 'lokasi', 'tingkat_status', 'tanggal_kejadian', 'latitude', 'longitude']);

        return view('bencana.index', compact('bencana', 'jenis', 'mapData'));
    }

    public function show(DataBencana $bencana)
    {
        $bencana->load(['jenis', 'berita' => fn($q) => $q->where('status', 'published'), 'updates']);
        return view('bencana.show', compact('bencana'));
    }
}
