<?php

namespace App\Filament\Widgets;

use App\Models\Kunjungan;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;
use Carbon\Carbon;

class KunjunganPerBulanChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 4;
    protected ?string $maxHeight = '300px';

    protected ?string $heading = 'Tren Kunjungan Bulanan';

    public ?int $year = null;
    public ?string $quarter = null;

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

        // Query manual pengganti Laravel Trend
        $data = Kunjungan::select(
            \Illuminate\Support\Facades\DB::raw("strftime('%Y-%m', tanggal) as date_val"),
            \Illuminate\Support\Facades\DB::raw('count(*) as aggregate')
        )
            ->whereBetween('tanggal', [$start, $end])
            ->groupBy('date_val')
            ->orderBy('date_val')
            ->get();

        $results = collect();
        $current = $start->copy();

        // Loop untuk memastikan setiap bulan ada datanya (isi 0 jika kosong)
        while ($current <= $end) {
            $key = $current->format('Y-m');
            $found = $data->firstWhere('date_val', $key);

            $results->push((object) [
                'date' => $current->format('Y-m-d'), // Format tanggal lengkap
                'aggregate' => $found ? $found->aggregate : 0,
                'label' => $current->translatedFormat('F'),
            ]);

            $current->addMonth();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kunjungan',
                    'data' => $results->pluck('aggregate'),
                    'backgroundColor' => 'rgba(96, 165, 250, 0.5)', // Blue-400
                    'borderColor' => '#60a5fa',
                    'borderWidth' => 1,
                    'barPercentage' => 0.6,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $results->pluck('label'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
