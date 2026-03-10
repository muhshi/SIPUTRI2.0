<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluasi extends Model
{
    protected $fillable = [
        'pegawai_id',
        'rating',
        'pengunjung_id',
        'tanggal_evaluasi'
    ];

    protected $casts = [
        'tanggal_evaluasi' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(PegawaiPst::class, 'pegawai_id');
    }

    public function pengunjung()
    {
        return $this->belongsTo(Kunjungan::class, 'pengunjung_id');
    }
}
