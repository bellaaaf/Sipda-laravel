<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name', 'email', 'no_telp', 'password',
        'role', 'is_active', 'google_id', 'avatar',
        'reset_token', 'reset_expiry',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'     => 'hashed',
            'is_active'    => 'boolean',
            'reset_expiry' => 'datetime',
        ];
    }

    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isPetugas(): bool  { return $this->role === 'petugas'; }
    public function isMasyarakat(): bool { return $this->role === 'masyarakat'; }

    public function laporan()       { return $this->hasMany(LaporanMasyarakat::class); }
    public function notifikasi()    { return $this->hasMany(Notifikasi::class); }
    public function logAktivitas()  { return $this->hasMany(LogAktivitas::class); }
    public function berita()        { return $this->hasMany(Berita::class, 'admin_id'); }
    public function arsipHoaks()    { return $this->hasMany(ArsipHoaks::class, 'petugas_id'); }
}
