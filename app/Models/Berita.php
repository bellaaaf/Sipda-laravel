<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';
    protected $fillable = ['bencana_id', 'judul', 'isi', 'foto', 'status', 'admin_id'];

    public function bencana() { return $this->belongsTo(DataBencana::class, 'bencana_id'); }
    public function admin()   { return $this->belongsTo(User::class, 'admin_id'); }
}
