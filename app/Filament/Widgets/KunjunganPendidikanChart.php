<?php

namespace App\Filament\Widgets;

use App\Models\Kunjungan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Carbon\Carbon;

class KunjunganPendidikanChart extends ChartWidget
{
    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = 4;
    protected ?string $maxHeight = '300px';

    protected ?string $heading = 'Distribusi Berdasarkan Pendidikan';

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

        $data = Kunjungan::select('pendidikan', DB::raw('count(*) as count'))
            ->whereBetween('tanggal', [$start, $end])
            ->whereNotNull('pendidikan')
            ->groupBy('pendidikan')
            ->pluck('count', 'pendidikan');

        return [
            'datasets' => [
                [
                    'label' => 'Kunjungan',
                    'data' => $data->values(),
                    'backgroundColor' => 'rgba(192, 132, 252, 0.5)', // Purple-400
                    'borderColor' => '#c084fc',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $data->keys(),
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