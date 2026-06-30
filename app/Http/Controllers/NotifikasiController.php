<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function unread(): JsonResponse
    {
        $notifs = Notifikasi::where('user_id', Auth::id())
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->take(10)
            ->get(['id', 'judul', 'pesan', 'tipe', 'url', 'created_at']);

        return response()->json($notifs);
    }

    public function baca(Notifikasi $notifikasi): JsonResponse
    {
        abort_if($notifikasi->user_id !== Auth::id(), 403);
        $notifikasi->update(['is_read' => true]);
        return response()->json(['ok' => true]);
    }

    public function bacaSemua(): JsonResponse
    {
        Notifikasi::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        return response()->json(['ok' => true]);
    }
}
