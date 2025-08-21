<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    protected $fillable = [
        'pegawai_id','rating','komentar','nama_pengunjung','ip_address','user_agent','submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}
