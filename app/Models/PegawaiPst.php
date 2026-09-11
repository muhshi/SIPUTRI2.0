<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PegawaiPst extends Model
{
    protected $fillable = [
        'user_id',
        'nip_bps',
        'nip',
        'nama_pegawai',
        'jabatan',
        'pangkat',
        'golongan',
        'foto_pegawai',
    ];

    protected static function booted(): void
    {
        static::saving(function (PegawaiPst $pegawai) {
            // Sinkronisasi data dari user jika user_id diset
            if ($pegawai->user_id) {
                $user = User::find($pegawai->user_id);
                if ($user) {
                    if (empty($pegawai->nama_pegawai)) {
                        $pegawai->nama_pegawai = $user->name;
                    }
                    if (empty($pegawai->nip) && $user->nip) {
                        $pegawai->nip = $user->nip;
                    }
                    if (empty($pegawai->jabatan) && $user->jabatan) {
                        $pegawai->jabatan = $user->jabatan;
                    }
                }
            } elseif ($pegawai->nip) {
                // Cari user berdasarkan NIP jika user_id belum terisi
                $user = User::where('nip', $pegawai->nip)->first();
                if ($user) {
                    $pegawai->user_id = $user->id;
                }
            }
        });

        static::saved(function (PegawaiPst $pegawai) {
            // Kompresi foto setelah disimpan/diupdate
            if ($pegawai->wasChanged('foto_pegawai') && $pegawai->foto_pegawai) {
                static::compressImage($pegawai->foto_pegawai);
            }
        });
    }

    /**
     * Kompresi gambar menggunakan GD Library.
     * Resize max 800px dan kualitas JPEG 80%.
     */
    public static function compressImage(string $path, int $maxWidth = 800, int $quality = 80): void
    {
        $disk = Storage::disk('public');

        if (!$disk->exists($path)) {
            return;
        }

        $fullPath = $disk->path($path);
        $imageInfo = @getimagesize($fullPath);

        if (!$imageInfo) {
            return;
        }

        $mime = $imageInfo['mime'];
        $origWidth = $imageInfo[0];
        $origHeight = $imageInfo[1];

        // Buat image resource berdasarkan tipe
        $source = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($fullPath),
            'image/png' => @imagecreatefrompng($fullPath),
            'image/webp' => @imagecreatefromwebp($fullPath),
            default => null,
        };

        if (!$source) {
            return;
        }

        // Hitung dimensi baru (pertahankan aspect ratio)
        $newWidth = $origWidth;
        $newHeight = $origHeight;

        if ($origWidth > $maxWidth) {
            $ratio = $maxWidth / $origWidth;
            $newWidth = $maxWidth;
            $newHeight = (int) round($origHeight * $ratio);
        }

        // Resize jika perlu
        if ($newWidth !== $origWidth || $newHeight !== $origHeight) {
            $resized = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency untuk PNG
            if ($mime === 'image/png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($source);
            $source = $resized;
        }

        // Simpan dengan kompresi
        match ($mime) {
            'image/jpeg' => imagejpeg($source, $fullPath, $quality),
            'image/png' => imagepng($source, $fullPath, 6), // 0-9, 6 = good compression
            'image/webp' => imagewebp($source, $fullPath, $quality),
            default => null,
        };

        imagedestroy($source);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class, 'pegawai_id');
    }

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class, 'pegawai_id');
    }

    public function evaluasis()
    {
        return $this->hasMany(Evaluasi::class, 'pegawai_id');
    }
}
