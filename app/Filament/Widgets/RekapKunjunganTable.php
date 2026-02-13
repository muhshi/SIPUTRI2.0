<?php

namespace App\Filament\Widgets;

use App\Models\Kunjungan;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RekapKunjunganTable extends TableWidget
{
    protected static ?int $sort = 20;
    protected static bool $isDiscovered = false;

    protected int|string|array $columnSpan = 6;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Kunjungan::query()
                    ->selectRaw("
                        MIN(id) as id,
                        strftime('%m', tanggal) as bulan,
                        COUNT(*) as total
                    ")
                    ->groupBy('bulan')
                    ->orderBy('bulan')
            )
            ->columns([
                Tables\Columns\TextColumn::make('bulan')
                    ->label('Bulan')
                    ->formatStateUsing(
                        fn($state) =>
                        Carbon::create()->month((int) $state)->translatedFormat('F')
                    ),

                Tables\Columns\TextColumn::make('total')
                    ->label('Jumlah Kunjungan'),
            ]);
    }
}
