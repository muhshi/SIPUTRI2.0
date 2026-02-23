<?php

namespace App\Filament\Widgets;

use App\Models\Presensi;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

use Illuminate\Support\Facades\DB;

class RekapPresensiTable extends TableWidget
{
    protected static ?int $sort = 21;
    protected static bool $isDiscovered = false;

    protected int|string|array $columnSpan = 6;

    public function table(Table $table): Table
    {
        $driver = DB::connection()->getDriverName();
        $monthExpr = $driver === 'sqlite'
            ? "strftime('%m', tanggal)"
            : "MONTH(tanggal)";

        return $table
            ->query(
                Presensi::query()
                    ->selectRaw("
                        MIN(id) as id,
                        $monthExpr as bulan,
                        SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as hadir,
                        SUM(CASE WHEN status != 'Hadir' THEN 1 ELSE 0 END) as tidak_hadir,
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

                Tables\Columns\TextColumn::make('hadir')
                    ->label('Hadir'),

                Tables\Columns\TextColumn::make('tidak_hadir')
                    ->label('Tidak Hadir'),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total'),
            ]);
    }
}
