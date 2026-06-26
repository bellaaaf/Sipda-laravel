<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBencana extends Model
{
    protected $table = 'jenis_bencana';
    protected $fillable = ['nama_bencana', 'icon'];

    public function bencana() { return $this->hasMany(DataBencana::class, 'jenis_id'); }
}
