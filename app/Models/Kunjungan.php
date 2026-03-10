<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'nama',
        'pendidikan',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'pekerjaan',
        'jenis_kelamin',
        'instansi',
        'email',
        'pemanfaatan',
        'tahun_lahir',
        'layanan',
        'status',
        'umur',
        'data_diinginkan',
        'alamat',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function pegawai()
    {
        return $this->belongsTo(PegawaiPst::class, 'pegawai_id');
    }
}
