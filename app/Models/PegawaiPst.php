<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiPst extends Model
{
    protected $fillable = [
        'nip_bps',
        'nip',
        'nama_pegawai',
        'jabatan',
        'pangkat',
        'golongan',
        'foto_pegawai',
    ];

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'pegawai_id');
    }
}
