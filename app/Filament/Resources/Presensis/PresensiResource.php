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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;


class PresensiResource extends Resource
{
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('pegawai');
    }

    protected static ?string $model = Presensi::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen';

    protected static ?string $navigationLabel = 'Presensi';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return PresensiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PresensisTable::configure($table);
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
