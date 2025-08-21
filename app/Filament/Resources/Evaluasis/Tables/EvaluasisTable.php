<?php

namespace App\Filament\Resources\Evaluasis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EvaluasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->getStateUsing(function ($record) {
                        $pegawai = [
                            ['nama' => 'Henri Wagiyanto S.Pt., M.Ec.Dev., M.A.'],
                            ['nama' =>'M. Masykuri Zaen, S.ST.'],
                            ['nama' =>'Paramitha Hanifia S.ST.'],
                            ['nama' =>'M. Abdul Muhshi S.ST.'],
                            ['nama' =>'Wiwi Wilujeng, K.SE., M.M.'],
                            ['nama' =>'Nur Kurniawati, S.ST.'],
                            ['nama' =>'Muhammad Guntur Ilham, S.Tr.Stat.'],
                            ['nama' =>'Nunung Susanti, A.Md.'],
                            ['nama' =>'Dyah Makutaning Dewi, S.Tr.Stat.'],
                            ['nama' =>'Musyafaah, A.Md.'],
                            ['nama' =>'Aris Sutikno, S.E.'],
                            ['nama' =>'Yudia Pratidina Hasibuan, S.ST.'],
                        ];

                        return $pegawai[$record->pegawai_id]['nama'] ?? 'Tidak Diketahui';
                    }),
                    
                TextColumn::make('rating')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
