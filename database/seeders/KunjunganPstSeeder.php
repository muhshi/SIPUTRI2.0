<?php

namespace Database\Seeders;

use App\Models\Kunjungan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class KunjunganPstSeeder extends Seeder
{
    /**
     * Seed the kunjungans table from pst.csv
     *
     * CSV columns (semicolon-delimited):
     * 0: id  |  1: tanggal  |  2: tahun  |  3: nama  |  4: jenis_kelamin
     * 5: email  |  6: tahun_lahir  |  7: umur  |  8: alamat  |  9: pendidikan
     * 10: pekerjaan  |  11: instansi  |  12: pemanfaatan_data  |  13: layanan
     * 14: data  |  15: saran  |  16: kepuasan  |  17: created_at  |  18: updated_at
     * 19: deleted_at
     */
    public function run(): void
    {
        $csvPath = storage_path('app/import/pst.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            return;
        }

        $file = fopen($csvPath, 'r');

        // Skip header row
        fgetcsv($file, 0, ';');

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            // Skip empty rows
            if (empty($row[0]) || count($row) < 15) {
                $skipped++;
                continue;
            }

            // Skip soft-deleted records (deleted_at is not NULL)
            $deletedAt = isset($row[19]) ? trim($row[19], '"') : 'NULL';
            if ($deletedAt !== 'NULL' && !empty($deletedAt)) {
                $skipped++;
                continue;
            }

            try {
                // Clean values - remove surrounding quotes and handle NULL
                $clean = fn($val) => ($val === 'NULL' || $val === null || $val === '') ? null : trim($val, '"');

                // Parse tahun_lahir - handle non-numeric values
                $tahunLahir = $clean($row[6]);
                $tahunLahir = ($tahunLahir && is_numeric($tahunLahir) && (int) $tahunLahir > 1900) ? (int) $tahunLahir : null;

                // Parse umur - handle non-numeric values
                $umur = $clean($row[7]);
                $umur = ($umur && is_numeric($umur) && (int) $umur > 0 && (int) $umur < 200) ? (int) $umur : null;

                Kunjungan::updateOrCreate(
                    ['id' => (int) $clean($row[0])],
                    [
                        'tanggal' => $clean($row[1]),
                        'nama' => $clean($row[3]),
                        'jenis_kelamin' => $clean($row[4]),
                        'email' => $clean($row[5]),
                        'tahun_lahir' => $tahunLahir,
                        'umur' => $umur,
                        'alamat' => $clean($row[8]),
                        'pendidikan' => $clean($row[9]),
                        'pekerjaan' => $clean($row[10]),
                        'instansi' => $clean($row[11]),
                        'pemanfaatan' => $clean($row[12]),  // CSV: pemanfaatan_data → DB: pemanfaatan
                        'layanan' => $clean($row[13]),
                        'data_diinginkan' => $clean($row[14]),  // CSV: data → DB: data_diinginkan
                        'created_at' => $clean($row[17]),
                        'updated_at' => $clean($row[18]),
                    ]
                );

                $imported++;
            } catch (\Exception $e) {
                Log::error("Failed to import kunjungan row: " . json_encode($row) . " Error: " . $e->getMessage());
                $this->command->error("Error importing row ID {$row[0]}: {$e->getMessage()}");
                $skipped++;
            }
        }

        fclose($file);
        $this->command->info("Kunjungan PST import complete: {$imported} imported, {$skipped} skipped.");
    }
}
