<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarLaporan extends Model
{
    protected $table = 'komentar_laporan';
    protected $fillable = ['laporan_id', 'user_id', 'nama_komentator', 'komentar'];

    public function laporan() { return $this->belongsTo(LaporanMasyarakat::class, 'laporan_id'); }
    public function user()    { return $this->belongsTo(User::class); }
}
