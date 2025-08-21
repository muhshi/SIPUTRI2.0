<?php

namespace App\Filament\Widgets;

use App\Models\Kunjungan;
use Filament\Widgets\ChartWidget;

class KunjunganChart extends ChartWidget
{
    // Judul chart
    public function getHeading(): string
    {
        return 'Rekap Kunjungan per Bulan';
    }

    protected function getData(): array
    {
        $year = (int) now()->format('Y');

        // Ambil total kunjungan per bulan untuk tahun berjalan
        $totals = Kunjungan::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->all();

        // Label bulan
        $labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $data[] = $totals[$m] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => "Kunjungan $year",
                    'data' => $data,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
