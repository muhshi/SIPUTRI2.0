<?php

namespace App\Filament\Widgets;

use App\Models\Presensi;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;
use Carbon\Carbon;

class PresensiChart extends ChartWidget
{
    protected static ?int $sort = 8;
    protected static bool $isDiscovered = false;
    protected int|string|array $columnSpan = 12;
    protected ?string $maxHeight = '300px';

    public ?int $year = null;
    public ?string $quarter = null;

    protected ?string $heading = 'Rekap Presensi Pegawai per Bulan';

    public function mount(): void
    {
        $this->year = now()->year;
        $this->quarter = 'q' . ceil(now()->month / 3);
    }

    #[On('update-filters')]
    public function updateFilters($year, $quarter): void
    {
        $this->year = intval($year) ?: now()->year;
        $this->quarter = $quarter;
        $this->updateChartData();
    }

    public function getData(): array
    {
        $year = $this->year ?? now()->year;
        $quarter = $this->quarter ?? 'q' . ceil(now()->month / 3);

        $startMonth = match ($quarter) {
            'q1' => 1,
            'q2' => 4,
            'q3' => 7,
            'q4' => 10,
            default => 1,
        };

        $start = Carbon::createFromDate($year, $startMonth, 1)->startOfMonth();
        $end = $start->copy()->addMonths(2)->endOfMonth();

        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        $dateExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m', tanggal)"
            : "DATE_FORMAT(tanggal, '%Y-%m')";

        $data = Presensi::selectRaw("$dateExpression as bulan, COUNT(*) as total")
            ->whereBetween('tanggal', [$start, $end])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->all();

        $labels = [];
        $values = [];
        $current = $start->copy();

        while ($current <= $end) {
            $key = $current->format('Y-m');
            $labels[] = $current->translatedFormat('F');
            $values[] = $data[$key] ?? 0;
            $current->addMonth();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => "Hadir $year",
                    'data' => $values,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)', // Amber-500 equivalent
                    'borderColor' => '#f59e0b',
                    'borderWidth' => 1,
                    'barPercentage' => 0.6,
                    'borderRadius' => 4,
                ],
            ],
        ];
    }

    public function getType(): string
    {
        return 'bar';
    }
}
