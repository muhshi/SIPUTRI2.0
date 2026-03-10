<?php

namespace App\Filament\Resources\PegawaiPsts;

use App\Filament\Resources\PegawaiPsts\Pages\CreatePegawaiPst;
use App\Filament\Resources\PegawaiPsts\Pages\EditPegawaiPst;
use App\Filament\Resources\PegawaiPsts\Pages\ListPegawaiPsts;
use App\Filament\Resources\PegawaiPsts\Schemas\PegawaiPstForm;
use App\Filament\Resources\PegawaiPsts\Tables\PegawaiPstsTable;
use App\Models\PegawaiPst;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PegawaiPstResource extends Resource
{
    protected static ?string $model = PegawaiPst::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen';

    protected static ?string $navigationLabel = 'Pegawai';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'nama_pegawai';

    public static function form(Schema $schema): Schema
    {
        return PegawaiPstForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PegawaiPstsTable::configure($table);
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
            'index' => ListPegawaiPsts::route('/'),
            'create' => CreatePegawaiPst::route('/create'),
            'edit' => EditPegawaiPst::route('/{record}/edit'),
        ];
    }
}
