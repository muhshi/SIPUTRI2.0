<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RatingStat extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 4;

    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Rating', '0 / 5')
                ->description('Rata-rata kepuasan')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
