<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    protected $fillable = ['nama', 'jabatan', 'foto_path'];

    public function evaluasis()
    {
        return $this->hasMany(Evaluasi::class);
    }
    
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }
}
