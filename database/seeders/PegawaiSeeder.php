<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pegawai;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Henri Wagiyanto S.Pt., M.Ec.Dev., M.A.',
            'M. Masykuri Zaen, S.ST.',
            'Paramitha Hanifia S.ST.',
            'M. Abdul Muhshi S.ST.',
            'Wiwi Wilujeng, K.SE., M.M.',
            'Nur Kurniawati, S.ST.',
            'Muhammad Guntur Ilham, S.Tr.Stat.',
            'Nunung Susanti, A.Md.',
            'Dyah Makutaning Dewi, S.Tr.Stat.',
            'Musyafaah, A.Md.',
            'Aris Sutikno, S.E.',
            'Yudia Pratidina Hasibuan, S.ST.'
        ];

        foreach ($names as $name) {
            Pegawai::create([
                'nama' => $name
            ]);
        }
    }
}
