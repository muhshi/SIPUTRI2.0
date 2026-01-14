<?php

namespace App\Filament\Resources\Kunjungans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class KunjungansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('nama')
                        ->label('Nama')
                        ->searchable()
                        ->sortable(),

                    TextColumn::make('instansi')
                        ->label('Instansi')
                        ->searchable(),

                    TextColumn::make('tanggal')
                        ->label('Tanggal Kunjungan')
                        ->date()
                        ->sortable(),

                    TextColumn::make('layanan')
                        ->label('Layanan')
                        ->searchable(),

                    TextColumn::make('email')
                        ->label('Email')
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true),

                    TextColumn::make('created_at')
                        ->label('Dibuat')
                        ->dateTime('d M Y H:i')
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
            ->filters([
                    // === Filter Tahun ===
                    Filter::make('tahun')
                        ->form([
                                Select::make('tahun')
                                    ->label('Pilih Tahun')
                                    ->options(function () {
                                        // range dinamis: 2018 .. tahun ini
                                        $years = range(2018, now()->year);
                                        return collect($years)->mapWithKeys(fn($y) => [$y => (string) $y])->all();
                                    })
                                    ->searchable()
                                    ->native(false),
                            ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query->when(
                                $data['tahun'] ?? null,
                                fn(Builder $q, $year) => $q->whereYear('tanggal', (int) $year)
                            );
                        })
                        ->indicateUsing(function (array $data): ?array {
                            return !empty($data['tahun'])
                                ? ['Tahun: ' . $data['tahun']]
                                : null;
                        }),

                    // === Filter Triwulan ===
                    Filter::make('triwulan')
                        ->form([
                                Select::make('triwulan')
                                    ->label('Pilih Triwulan')
                                    ->options([
                                            1 => 'Triwulan 1 (Jan–Mar)',
                                            2 => 'Triwulan 2 (Apr–Jun)',
                                            3 => 'Triwulan 3 (Jul–Sep)',
                                            4 => 'Triwulan 4 (Okt–Des)',
                                        ])
                                    ->native(false),
                            ])
                        ->query(function (Builder $query, array $data): Builder {
                            $quarter = (int) ($data['triwulan'] ?? 0);
                            if (!$quarter) {
                                return $query;
                            }

                            $ranges = [
                                1 => [1, 3],
                                2 => [4, 6],
                                3 => [7, 9],
                                4 => [10, 12],
                            ];

                            [$start, $end] = $ranges[$quarter];

                            return $query->whereMonth('tanggal', '>=', $start)
                                ->whereMonth('tanggal', '<=', $end);
                        })
                        ->indicateUsing(function (array $data): ?array {
                            return !empty($data['triwulan'])
                                ? ['Triwulan: Q' . $data['triwulan']]
                                : null;
                        }),
                ])
            ->recordActions([
                    ViewAction::make(),
                    EditAction::make(),
                ])
            ->toolbarActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make(),
                    ]),
                ])
            ->defaultSort('tanggal', 'desc');
    }
}
