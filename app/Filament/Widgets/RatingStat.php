<?php

namespace App\Filament\Widgets;

use App\Models\Evaluasi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RatingStat extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 4;

    protected function getStats(): array
    {
        $average = Evaluasi::avg('rating') ?? 0;
        $count = Evaluasi::count();

        return [
            Stat::make(
                'Jumlah Rating',
                number_format($average, 2) . ' / 5'
            )
                ->description($count . ' Evaluasi masuk')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
