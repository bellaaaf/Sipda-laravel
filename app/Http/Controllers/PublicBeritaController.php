<?php

namespace App\Http\Controllers;

use App\Models\Berita;

class PublicBeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::with(['bencana.jenis', 'admin'])
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('berita.index', compact('berita'));
    }

    public function show(Berita $berita)
    {
        if ($berita->status !== 'published') abort(404);
        $berita->load(['bencana.jenis', 'admin']);

        $related = Berita::with('bencana.jenis')
            ->where('status', 'published')
            ->where('id', '!=', $berita->id)
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('berita.show', compact('berita', 'related'));
    }
}
