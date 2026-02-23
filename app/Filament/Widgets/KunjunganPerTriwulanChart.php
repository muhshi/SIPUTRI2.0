<?php

namespace App\Filament\Widgets;

use App\Models\Kunjungan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class KunjunganPerTriwulanChart extends ChartWidget
{
    protected static ?int $sort = 7;
    protected int|string|array $columnSpan = 6;
    protected ?string $maxHeight = '300px';

    public static function canView(): bool
    {
        return false;
    }

    protected ?string $heading = 'Tren Kunjungan Triwulan';

    protected function getData(): array
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $quarterExpr = "CASE 
                    WHEN strftime('%m', tanggal) BETWEEN '01' AND '03' THEN 'Triwulan 1'
                    WHEN strftime('%m', tanggal) BETWEEN '04' AND '06' THEN 'Triwulan 2'
                    WHEN strftime('%m', tanggal) BETWEEN '07' AND '09' THEN 'Triwulan 3'
                    WHEN strftime('%m', tanggal) BETWEEN '10' AND '12' THEN 'Triwulan 4'
                END";
        } else {
            $quarterExpr = "CONCAT('Triwulan ', QUARTER(tanggal))";
        }

        $data = Kunjungan::select(
            DB::raw("$quarterExpr as quarter"),
            DB::raw('count(*) as count')
        )
            ->whereNotNull('tanggal')
            ->groupBy('quarter')
            ->orderBy('quarter')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kunjungan',
                    'data' => $data->pluck('count'),
                    'backgroundColor' => 'rgba(99, 102, 241, 0.5)',
                    'borderColor' => '#6366f1',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('quarter'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
