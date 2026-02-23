<?php

namespace App\Console\Commands;

use App\Models\PegawaiPst;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CompressPegawaiPhotos extends Command
{
    protected $signature = 'photos:compress {--max-width=800 : Max width in pixels} {--quality=80 : JPEG quality (1-100)}';
    protected $description = 'Kompresi semua foto pegawai PST yang sudah ada di storage';

    public function handle(): int
    {
        $maxWidth = (int) $this->option('max-width');
        $quality = (int) $this->option('quality');
        $disk = Storage::disk('public');

        $files = $disk->files('pegawai-pst-photos');
        $files = array_filter($files, fn($f) => !str_contains($f, '.gitignore'));

        if (empty($files)) {
            $this->warn('Tidak ada foto untuk dikompresi.');
            return 0;
        }

        $this->info("Ditemukan " . count($files) . " foto. Memulai kompresi...");
        $bar = $this->output->createProgressBar(count($files));

        $totalBefore = 0;
        $totalAfter = 0;

        foreach ($files as $file) {
            $fullPath = $disk->path($file);
            $sizeBefore = filesize($fullPath);
            $totalBefore += $sizeBefore;

            PegawaiPst::compressImage($file, $maxWidth, $quality);

            clearstatcache(true, $fullPath);
            $sizeAfter = filesize($fullPath);
            $totalAfter += $sizeAfter;

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $savedMB = round(($totalBefore - $totalAfter) / 1024 / 1024, 2);
        $percentage = round((1 - $totalAfter / $totalBefore) * 100, 1);

        $this->info("✅ Selesai!");
        $this->table(
            ['Keterangan', 'Nilai'],
            [
                ['Total file', count($files)],
                ['Ukuran sebelum', round($totalBefore / 1024 / 1024, 2) . ' MB'],
                ['Ukuran sesudah', round($totalAfter / 1024 / 1024, 2) . ' MB'],
                ['Hemat', "{$savedMB} MB ({$percentage}%)"],
            ]
        );

        return 0;
    }
}
