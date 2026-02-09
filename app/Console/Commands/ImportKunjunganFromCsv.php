<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kunjungan;
use Carbon\Carbon;

class ImportKunjunganFromCsv extends Command
{
    protected $signature = 'app:import-kunjungan-from-csv';
    protected $description = 'Import data kunjungan dari file CSV';

    public function handle()
    {
        $path = storage_path('app/import/pst.csv');

        if (!file_exists($path)) {
            $this->error('File pst.csv tidak ditemukan.');
            return;
        }

        // ⬇️ INI BAGIAN YANG KAMU TANYA
        $rows = array_map(
            fn ($line) => str_getcsv($line, ';'),
            file($path, FILE_SKIP_EMPTY_LINES)
        );

        $header = array_map(
            fn ($h) => trim(strtolower($h)),
            $rows[0]
        );

        unset($rows[0]);

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            Kunjungan::create([
                'nama'            => $data['nama'],
                'tanggal'         => Carbon::parse($data['tanggal']),
                'jenis_kelamin'   => $data['jenis_kelamin'] ?? null,
                'email'           => $data['email'] ?? null,
                'tahun_lahir'     => $data['tahun_lahir'] ?? null,
                'umur'            => $data['umur'] ?? null,
                'alamat'          => $data['alamat'] ?? null,
                'pendidikan'      => $data['pendidikan'] ?? null,
                'pekerjaan'       => $data['pekerjaan'] ?? null,
                'instansi'        => $data['instansi'] ?? null,
                'pemanfaatan'     => $data['pemanfaatan_data'] ?? null,
                'layanan'         => $data['layanan'] ?? null,
                'data_diinginkan' => $data['data'] ?? null,
            ]);
        }

        $this->info('Import selesai.');
    }
}
