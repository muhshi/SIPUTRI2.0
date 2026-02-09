<?php

namespace App\Filament\Widgets;

use App\Models\Kunjungan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KunjunganStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    // SETENGAH DASHBOARD
    protected int|string|array $columnSpan = 4;

    // ⬅️ KUNCI UTAMA: STAT SEJAJAR
    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Kunjungan', Kunjungan::count())
                ->description('Semua data kunjungan')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
        ];
    }
}
