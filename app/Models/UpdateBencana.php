<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateBencana extends Model
{
    protected $table = 'update_bencana';
    protected $fillable = ['bencana_id', 'status', 'deskripsi', 'petugas_id'];

    public function bencana() { return $this->belongsTo(DataBencana::class, 'bencana_id'); }
    public function petugas() { return $this->belongsTo(User::class, 'petugas_id'); }
}
