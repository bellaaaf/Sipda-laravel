<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JenisBencana;
use App\Models\LaporanMasyarakat;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function create()
    {
        $jenis = JenisBencana::orderBy('nama_bencana')->get();
        return view('user.laporan.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi_kejadian'  => 'required|string|max:255',
            'jenis_bencana'    => 'required|string',
            'deskripsi'        => 'required|string|min:20',
            'tingkat_keparahan'=> 'required|in:Ringan,Sedang,Berat,Sangat Berat',
            'tanggal_kejadian' => 'required|date|before_or_equal:today',
            'foto'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'deskripsi.min'           => 'Deskripsi minimal 20 karakter.',
            'tanggal_kejadian.before_or_equal' => 'Tanggal kejadian tidak boleh di masa depan.',
            'foto.image'              => 'File harus berupa gambar.',
            'foto.max'                => 'Ukuran foto maksimal 2MB.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('laporan', 'public');
        }

        $user = Auth::user();
        $laporan = LaporanMasyarakat::create([
            'user_id'          => $user->id,
            'nama_pelapor'     => $user->full_name,
            'email_pelapor'    => $user->email,
            'telepon'          => $request->telepon ?? $user->no_telp,
            'lokasi_kejadian'  => $request->lokasi_kejadian,
            'jenis_bencana'    => $request->jenis_bencana,
            'deskripsi'        => $request->deskripsi,
            'tingkat_keparahan'=> $request->tingkat_keparahan,
            'korban_jiwa'      => $request->korban_jiwa ?? 0,
            'korban_luka'      => $request->korban_luka ?? 0,
            'rumah_rusak'      => $request->rumah_rusak ?? 0,
            'latitude'         => $request->latitude,
            'longitude'        => $request->longitude,
            'foto'             => $fotoPath,
            'status'           => 'pending',
            'ip_address'       => $request->ip(),
            'tanggal_kejadian' => $request->tanggal_kejadian,
        ]);

        LogAktivitas::create([
            'user_id'    => $user->id,
            'aksi'       => 'Buat Laporan',
            'deskripsi'  => "Laporan #{$laporan->id} dibuat: {$request->jenis_bencana} di {$request->lokasi_kejadian}",
            'ip_address' => $request->ip(),
        ]);

        // Fan-out notifikasi ke semua admin dan petugas
        $admins = User::where('role', 'admin')->where('is_active', true)->get();
        foreach ($admins as $admin) {
            Notifikasi::create([
                'user_id' => $admin->id,
                'judul'   => 'Laporan Bencana Baru',
                'pesan'   => "{$request->jenis_bencana} di {$request->lokasi_kejadian} — dilaporkan oleh {$user->full_name} (#{$laporan->id})",
                'tipe'    => 'laporan',
                'url'     => route('admin.laporan.show', $laporan),
            ]);
        }
        $petugasList = User::where('role', 'petugas')->where('is_active', true)->get();
        foreach ($petugasList as $petugas) {
            Notifikasi::create([
                'user_id' => $petugas->id,
                'judul'   => 'Laporan Bencana Baru',
                'pesan'   => "{$request->jenis_bencana} di {$request->lokasi_kejadian} — dilaporkan oleh {$user->full_name} (#{$laporan->id})",
                'tipe'    => 'laporan',
                'url'     => route('petugas.laporan.show', $laporan),
            ]);
        }

        return redirect()->route('user.laporan.index')->with('success', 'Laporan berhasil dikirim! Petugas akan segera meninjau laporan Anda.');
    }

    public function index()
    {
        $laporan = LaporanMasyarakat::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('user.laporan.index', compact('laporan'));
    }

    public function show(LaporanMasyarakat $laporan)
    {
        if ($laporan->user_id !== Auth::id()) abort(403);
        $laporan->load('komentar.user');
        return view('user.laporan.show', compact('laporan'));
    }
}
