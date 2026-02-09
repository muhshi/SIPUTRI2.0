<?php

namespace App\Filament\Widgets;

use App\Models\Kunjungan;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;
use Carbon\Carbon;

class KunjunganChart extends ChartWidget
{
    protected static ?int $sort = 7;
    protected static bool $isDiscovered = false;
    protected ?string $maxHeight = '300px';
    protected int|string|array $columnSpan = 12;

    public ?int $year = null;
    public ?string $quarter = null;

    protected ?string $heading = 'Rekap Kunjungan per Bulan';

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

    protected function getData(): array
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

        // Ambil total kunjungan per bulan dalam rentang triwulan
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        $dateExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $data = Kunjungan::selectRaw("$dateExpression as bulan, COUNT(*) as total")
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->all();

        // Siapkan label dan data untuk 3 bulan dalam triwulan tersebut
        $labels = [];
        $values = [];
        $current = $start->copy();

        while ($current <= $end) {
            $key = $current->format('Y-m');
            $labels[] = $current->translatedFormat('F'); // Nama bulan full
            $values[] = $data[$key] ?? 0;
            $current->addMonth();
        }

        return [
            'datasets' => [
                [
                    'label' => "Kunjungan $year",
                    'data' => $values,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)', // Emerald-500 equivalent
                    'borderColor' => '#10b981',
                    'borderWidth' => 1,
                    'barPercentage' => 0.6,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
