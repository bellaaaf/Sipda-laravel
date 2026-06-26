<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanMasyarakat;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan      = LaporanMasyarakat::with('user')->orderByDesc('created_at')->get();
        $statusCounts = $laporan->groupBy('status')->map->count();
        return view('admin.laporan.index', compact('laporan', 'statusCounts'));
    }

    public function show(LaporanMasyarakat $laporan)
    {
        $laporan->load(['user', 'komentar.user', 'arsipHoaks.petugas']);
        return view('admin.laporan.show', compact('laporan'));
    }

    public function updateStatus(Request $request, LaporanMasyarakat $laporan)
    {
        $request->validate([
            'status'          => 'required|in:pending,diproses,selesai,hoaks,ditolak,ditinjau',
            'catatan_petugas' => 'nullable|string|max:500',
        ]);

        $statusLama = $laporan->status;
        $laporan->update([
            'status'          => $request->status,
            'catatan_petugas' => $request->catatan_petugas,
        ]);

        if ($laporan->user_id) {
            $statusLabel = [
                'diproses' => 'sedang diproses',
                'selesai'  => 'telah selesai ditangani',
                'hoaks'    => 'ditandai sebagai hoaks',
                'ditolak'  => 'ditolak',
                'ditinjau' => 'sedang ditinjau ulang',
            ];
            Notifikasi::create([
                'user_id' => $laporan->user_id,
                'judul'   => 'Status Laporan Diperbarui',
                'pesan'   => "Laporan #{$laporan->id} Anda " . ($statusLabel[$request->status] ?? $request->status) . ".",
                'tipe'    => 'info',
                'url'     => route('user.laporan.show', $laporan),
            ]);
        }

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aksi'      => 'Update Status Laporan',
            'deskripsi' => "Laporan #{$laporan->id} status diubah dari {$statusLama} ke {$request->status}",
            'ip_address'=> request()->ip(),
        ]);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function destroy(LaporanMasyarakat $laporan)
    {
        $laporan->delete();
        return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
