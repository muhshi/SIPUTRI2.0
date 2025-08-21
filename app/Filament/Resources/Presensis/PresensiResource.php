<?php

namespace App\Filament\Resources\Presensis;

use App\Filament\Resources\Presensis\Pages\CreatePresensi;
use App\Filament\Resources\Presensis\Pages\EditPresensi;
use App\Filament\Resources\Presensis\Pages\ListPresensis;
use App\Filament\Resources\Presensis\Schemas\PresensiForm;
use App\Filament\Resources\Presensis\Tables\PresensisTable;
use App\Models\Presensi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;


class PresensiResource extends Resource
{
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('pegawai');
    }

    protected static ?string $model = Presensi::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return PresensiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
    return $table
        ->columns([
            TextColumn::make('pegawai.nama')->label('Nama Pegawai')->sortable()->searchable(),
            TextColumn::make('tanggal')->date(),
            TextColumn::make('jam_masuk')->time(),
            TextColumn::make('jam_selesai')->time(),
            TextColumn::make('status'),
        ])
        ->filters([
            // your filters
        ]);
    }

    public static function getRelations(): array
    { 
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPresensis::route('/'),
            'create' => CreatePresensi::route('/create'),
            'edit' => EditPresensi::route('/{record}/edit'),
        ];
    }
}
