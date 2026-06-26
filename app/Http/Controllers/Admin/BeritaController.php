<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\DataBencana;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        $berita       = Berita::with(['bencana.jenis', 'admin'])->orderByDesc('created_at')->get();
        $bencana      = DataBencana::with('jenis')->orderByDesc('tanggal_kejadian')->get();
        $statusCounts = $berita->groupBy('status')->map->count();
        return view('admin.berita.index', compact('berita', 'bencana', 'statusCounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'      => 'required|string|max:255',
            'isi'        => 'required|string|min:20',
            'status'     => 'required|in:published,draft',
            'bencana_id' => 'nullable|exists:data_bencana,id',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('foto');
        $data['admin_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('berita', 'public');
        }

        Berita::create($data);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aksi'      => 'Buat Berita',
            'deskripsi' => "Berita dibuat: {$request->judul}",
            'ip_address'=> request()->ip(),
        ]);

        return back()->with('success', 'Berita berhasil disimpan.');
    }

    public function update(Request $request, Berita $berita)
    {
        $request->validate([
            'judul'  => 'required|string|max:255',
            'isi'    => 'required|string|min:20',
            'status' => 'required|in:published,draft',
            'foto'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['foto', '_token', '_method']);

        if ($request->hasFile('foto')) {
            if ($berita->foto) Storage::disk('public')->delete($berita->foto);
            $data['foto'] = $request->file('foto')->store('berita', 'public');
        }

        $berita->update($data);

        return back()->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita)
    {
        if ($berita->foto) Storage::disk('public')->delete($berita->foto);
        $berita->delete();
        return back()->with('success', 'Berita berhasil dihapus.');
    }
}
