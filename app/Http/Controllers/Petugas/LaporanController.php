<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\ArsipHoaks;
use App\Models\KomentarLaporan;
use App\Models\LaporanMasyarakat;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanMasyarakat::with('user')->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $laporan = $query->paginate(20)->withQueryString();

        $statusCounts = LaporanMasyarakat::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('petugas.laporan.index', compact('laporan', 'statusCounts'));
    }

    public function show(LaporanMasyarakat $laporan)
    {
        $laporan->load(['user', 'komentar.user', 'arsipHoaks.petugas']);
        return view('petugas.laporan.show', compact('laporan'));
    }

    public function tinjau(Request $request, LaporanMasyarakat $laporan)
    {
        $request->validate([
            'status'          => 'required|in:diproses,selesai,hoaks,ditolak,ditinjau',
            'catatan_petugas' => 'nullable|string|max:500',
        ]);

        $laporan->update([
            'status'          => $request->status,
            'catatan_petugas' => $request->catatan_petugas,
        ]);

        if ($request->status === 'hoaks') {
            $request->validate(['alasan_hoaks' => 'required|string|min:10']);
            ArsipHoaks::updateOrCreate(
                ['laporan_id' => $laporan->id],
                [
                    'petugas_id'   => Auth::id(),
                    'alasan'       => $request->alasan_hoaks,
                    'tanggal_arsip'=> now(),
                ]
            );
        }

        if ($request->filled('komentar')) {
            KomentarLaporan::create([
                'laporan_id'     => $laporan->id,
                'user_id'        => Auth::id(),
                'nama_komentator'=> Auth::user()->full_name . ' (Petugas)',
                'komentar'       => $request->komentar,
            ]);
        }

        if ($laporan->user_id) {
            Notifikasi::create([
                'user_id' => $laporan->user_id,
                'judul'   => 'Status Laporan Diperbarui',
                'pesan'   => "Laporan #{$laporan->id} Anda telah diperbarui statusnya menjadi {$request->status}.",
                'tipe'    => 'info',
                'url'     => route('user.laporan.show', $laporan),
            ]);
        }

        LogAktivitas::create([
            'user_id'   => Auth::id(),
            'aksi'      => 'Tinjau Laporan',
            'deskripsi' => "Laporan #{$laporan->id} ditinjau: status → {$request->status}",
            'ip_address'=> request()->ip(),
        ]);

        return back()->with('success', 'Laporan berhasil diperbarui.');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new LaporanExport($request), 'laporan-bencana-' . now()->format('Ymd') . '.xlsx');
    }
}
