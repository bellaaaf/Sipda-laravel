<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanMasyarakat extends Model
{
    protected $table = 'laporan_masyarakat';
    protected $fillable = [
        'user_id', 'nama_pelapor', 'email_pelapor', 'telepon',
        'lokasi_kejadian', 'jenis_bencana', 'deskripsi', 'tingkat_keparahan',
        'korban_jiwa', 'korban_luka', 'rumah_rusak',
        'latitude', 'longitude', 'foto', 'status', 'catatan_petugas',
        'ip_address', 'tanggal_kejadian',
    ];

    protected function casts(): array
    {
        return ['tanggal_kejadian' => 'date'];
    }

    public function user()       { return $this->belongsTo(User::class); }
    public function komentar()   { return $this->hasMany(KomentarLaporan::class, 'laporan_id'); }
    public function arsipHoaks() { return $this->hasOne(ArsipHoaks::class, 'laporan_id'); }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'   => '<span class="badge bg-warning text-dark">⏳ Pending</span>',
            'diproses'  => '<span class="badge bg-info">🔄 Diproses</span>',
            'selesai'   => '<span class="badge bg-success">✅ Selesai</span>',
            'hoaks'     => '<span class="badge bg-secondary">🚫 Hoaks</span>',
            'ditolak'   => '<span class="badge bg-danger">❌ Ditolak</span>',
            'ditinjau'  => '<span class="badge bg-primary">🔍 Ditinjau</span>',
            default     => '<span class="badge bg-secondary">'.$this->status.'</span>',
        };
    }
}
