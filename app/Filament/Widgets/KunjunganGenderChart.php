<?php

namespace App\Filament\Widgets;

use App\Models\Kunjungan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Carbon\Carbon;

class KunjunganGenderChart extends ChartWidget
{
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 4;
    protected ?string $maxHeight = '300px';

    protected ?string $heading = 'Distribusi Berdasarkan Jenis Kelamin';

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

        $data = Kunjungan::select('jenis_kelamin', DB::raw('count(*) as count'))
            ->whereBetween('tanggal', [$start, $end])
            ->whereNotNull('jenis_kelamin')
            ->groupBy('jenis_kelamin')
            ->pluck('count', 'jenis_kelamin');

        return [
            'datasets' => [
                [
                    'label' => 'Kunjungan',
                    'data' => $data->values(),
                    'backgroundColor' => [
                        '#60a5fa', // Blue-400
                        '#f472b6', // Pink-400
                        '#94a3b8', // Slate-400
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $data->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}