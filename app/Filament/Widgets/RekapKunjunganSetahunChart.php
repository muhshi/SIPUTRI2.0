<?php

namespace App\Filament\Widgets;

use App\Models\Kunjungan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class RekapKunjunganSetahunChart extends ChartWidget
{
    protected static ?int $sort = 8;
    protected int|string|array $columnSpan = 12;
    protected ?string $maxHeight = '320px';

    protected ?string $heading = 'Rekap Kunjungan Setahun';
    protected ?string $description = 'Total Kunjungan Januari – Desember';

    public ?int $year = null;

    public function mount(): void
    {
        $this->year = now()->year;
    }

    #[On('update-filters')]
    public function updateFilters($year): void
    {
        $this->year = intval($year) ?: now()->year;
        $this->updateChartData();
    }

    protected function getData(): array
    {
        $year = $this->year ?? now()->year;

        // Siapkan 12 bulan default = 0
        $monthlyData = collect(range(1, 12))->mapWithKeys(fn($month) => [$month => 0]);

        // Ambil data dari database
        $driver = DB::connection()->getDriverName();
        $monthExpr = $driver === 'sqlite'
            ? "strftime('%m', tanggal)"
            : "MONTH(tanggal)";

        $data = Kunjungan::select(
            DB::raw("$monthExpr as month"),
            DB::raw('count(*) as count')
        )
            ->whereYear('tanggal', $year)
            ->groupBy('month')
            ->pluck('count', 'month');

        // Masukkan data hasil query ke array bulan
        foreach ($data as $month => $count) {
            $monthlyData[intval($month)] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kunjungan',
                    'data' => $monthlyData->values()->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.15)',
                    'borderWidth' => 3,
                    'tension' => 0.4,
                    'fill' => true,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0,0,0,0.05)',
                    ],
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
