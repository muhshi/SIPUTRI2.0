<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    protected static function booted(): void
    {
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
