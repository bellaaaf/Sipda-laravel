<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipHoaks extends Model
{
    protected $table = 'arsip_hoaks';
    protected $fillable = ['laporan_id', 'petugas_id', 'alasan', 'tanggal_arsip'];

    protected function casts(): array
    {
        return ['tanggal_arsip' => 'datetime'];
    }

    public function laporan() { return $this->belongsTo(LaporanMasyarakat::class, 'laporan_id'); }
    public function petugas() { return $this->belongsTo(User::class, 'petugas_id'); }
}
