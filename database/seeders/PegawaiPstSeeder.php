<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PegawaiPst;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PegawaiPstSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = storage_path('app/import/Pegawai PST - Sheet1.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            return;
        }

        $file = fopen($csvPath, 'r');

        // Skip header row
        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            // Mapping:
            // 0: NIP BPS
            // 1: NIP
            // 2: Nama Pegawai
            // 3: Jabatan
            // 4: Pangkat
            // 5: Golongan
            // 6: Foto Pegawai

            try {
                PegawaiPst::updateOrCreate(
                    ['nip_bps' => $row[0]],
                    [
                        'nip' => $row[1] ?? null,
                        'nama_pegawai' => $row[2],
                        'jabatan' => $row[3] ?? null,
                        'pangkat' => $row[4] ?? null,
                        'golongan' => $row[5] ?? null,
                        'foto_pegawai' => !empty($row[6]) ? $row[6] : null,
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Failed to import row: " . json_encode($row) . " Error: " . $e->getMessage());
                $this->command->error("Error importing row: {$row[2]}");
            }
        }

        fclose($file);
        $this->command->info('Pegawai PST imported successfully.');
    }
}
