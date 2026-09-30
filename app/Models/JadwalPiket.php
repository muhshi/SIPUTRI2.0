<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPiket extends Model
{
    protected $fillable = [
        'pegawai_pst_id',
        'tanggal',
        'jenis_piket',
        'shift',
        'is_notified',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_notified' => 'boolean',
    ];

    public function pegawaiPst()
    {
        return $this->belongsTo(PegawaiPst::class, 'pegawai_pst_id');
    }
}
