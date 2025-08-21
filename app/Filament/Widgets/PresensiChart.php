<?php

namespace App\Filament\Widgets;

use App\Models\Presensi;
use Filament\Widgets\ChartWidget;

class PresensiChart extends ChartWidget
{
    // Heading harus public, bukan protected
    public function getHeading(): string
    {
        return 'Rekap Presensi Pegawai per Bulan';
    }

    public function getData(): array
    {
        $year = (int) now()->format('Y');

        $totals = Presensi::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
            ->whereYear('tanggal', $year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->all();

        $labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $data[] = $totals[$m] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => "Hadir $year",
                    'data' => $data,
                ],
            ],
        ];
    }

    public function getType(): string
    {
        return 'bar';
    }
}
