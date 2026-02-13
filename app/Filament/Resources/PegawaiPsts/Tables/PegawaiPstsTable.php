<?php

namespace App\Filament\Resources\PegawaiPsts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\Storage;


class PegawaiPstsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nip_bps')
                    ->label('NIP BPS')
                    ->searchable(),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable(),
                TextColumn::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->searchable(),
                TextColumn::make('pangkat')
                    ->label('Pangkat')
                    ->searchable(),
                TextColumn::make('golongan')
                    ->label('Golongan')
                    ->searchable(),
                ImageColumn::make('foto_pegawai')
                    ->label('Foto Pegawai')
                    ->size(40)
                    ->circular()
                    ->url(
                        fn($record) =>
                        $record->foto_pegawai
                        ? Storage::url($record->foto_pegawai)
                        : asset('images/avatar-placeholder.png')
                    ),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
