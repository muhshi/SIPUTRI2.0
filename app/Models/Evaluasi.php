<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    protected $fillable = ['pegawai_id', 'rating', 'pengunjung_id'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
