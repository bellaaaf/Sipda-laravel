<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataBencana extends Model
{
    protected $table = 'data_bencana';
    protected $fillable = [
        'jenis_id', 'lokasi', 'tingkat_status', 'tanggal_kejadian',
        'deskripsi', 'latitude', 'longitude', 'foto', 'admin_id',
    ];

    protected function casts(): array
    {
        return ['tanggal_kejadian' => 'date'];
    }

    public function jenis()      { return $this->belongsTo(JenisBencana::class, 'jenis_id'); }
    public function admin()      { return $this->belongsTo(User::class, 'admin_id'); }
    public function berita()     { return $this->hasMany(Berita::class, 'bencana_id'); }
    public function updates()    { return $this->hasMany(UpdateBencana::class, 'bencana_id')->orderByDesc('created_at'); }

    public function getStatusColorAttribute(): string
    {
        return match($this->tingkat_status) {
            'Darurat' => 'danger',
            'Siaga'   => 'primary',
            'Waspada' => 'warning',
            'Aman'    => 'success',
            default   => 'secondary',
        };
    }
}
