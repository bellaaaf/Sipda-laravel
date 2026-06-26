<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataBencana;
use App\Models\JenisBencana;
use App\Models\LogAktivitas;
use App\Models\UpdateBencana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BencanaController extends Controller
{
    public function index()
    {
        $bencana = DataBencana::with('jenis')->orderByDesc('tanggal_kejadian')->get();
        $jenis   = JenisBencana::orderBy('nama_bencana')->get();
        return view('admin.bencana.index', compact('bencana', 'jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_id'         => 'required|exists:jenis_bencana,id',
            'lokasi'           => 'required|string|max:255',
            'tingkat_status'   => 'required|in:Darurat,Siaga,Waspada,Aman',
            'tanggal_kejadian' => 'required|date',
            'deskripsi'        => 'required|string',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'foto'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except('foto');
        $data['admin_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('bencana', 'public');
        }

        $bencana = DataBencana::create($data);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aksi'      => 'Tambah Bencana',
            'deskripsi' => "Bencana #{$bencana->id} ditambahkan: {$bencana->jenis->nama_bencana} di {$bencana->lokasi}",
            'ip_address'=> request()->ip(),
        ]);

        return back()->with('success', 'Data bencana berhasil ditambahkan.');
    }

    public function update(Request $request, DataBencana $bencana)
    {
        $request->validate([
            'jenis_id'         => 'required|exists:jenis_bencana,id',
            'lokasi'           => 'required|string|max:255',
            'tingkat_status'   => 'required|in:Darurat,Siaga,Waspada,Aman',
            'tanggal_kejadian' => 'required|date',
            'deskripsi'        => 'required|string',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'foto'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->except(['foto', '_token', '_method']);

        if ($request->hasFile('foto')) {
            if ($bencana->foto) Storage::disk('public')->delete($bencana->foto);
            $data['foto'] = $request->file('foto')->store('bencana', 'public');
        }

        $bencana->update($data);

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aksi'      => 'Edit Bencana',
            'deskripsi' => "Bencana #{$bencana->id} diperbarui",
            'ip_address'=> request()->ip(),
        ]);

        return back()->with('success', 'Data bencana berhasil diperbarui.');
    }

    public function destroy(DataBencana $bencana)
    {
        if ($bencana->foto) Storage::disk('public')->delete($bencana->foto);
        $bencana->delete();

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aksi'      => 'Hapus Bencana',
            'deskripsi' => "Bencana #{$bencana->id} dihapus",
            'ip_address'=> request()->ip(),
        ]);

        return back()->with('success', 'Data bencana berhasil dihapus.');
    }

    public function updatePerkembangan(Request $request, DataBencana $bencana)
    {
        $request->validate([
            'status'   => 'required|in:Darurat,Siaga,Waspada,Aman',
            'deskripsi'=> 'required|string|min:10',
        ]);

        UpdateBencana::create([
            'bencana_id' => $bencana->id,
            'status'     => $request->status,
            'deskripsi'  => $request->deskripsi,
            'petugas_id' => Auth::id(),
        ]);

        $bencana->update(['tingkat_status' => $request->status]);

        return back()->with('success', 'Update perkembangan bencana berhasil disimpan.');
    }
}
