<?php

namespace App\Filament\Resources\Kunjungans;

use App\Filament\Resources\Kunjungans\Pages\CreateKunjungan;
use App\Filament\Resources\Kunjungans\Pages\EditKunjungan;
use App\Filament\Resources\Kunjungans\Pages\ListKunjungans;
use App\Filament\Resources\Kunjungans\Pages\ViewKunjungan;
use App\Filament\Resources\Kunjungans\Schemas\KunjunganForm;
use App\Filament\Resources\Kunjungans\Schemas\KunjunganInfolist;
use App\Filament\Resources\Kunjungans\Tables\KunjungansTable;
use App\Models\Kunjungan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KunjunganResource extends Resource
{
    protected static ?string $model = Kunjungan::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return KunjunganForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KunjunganInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KunjungansTable::configure($table);
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
            'index' => ListKunjungans::route('/'),
            'create' => CreateKunjungan::route('/create'),
            'view' => ViewKunjungan::route('/{record}'),
            'edit' => EditKunjungan::route('/{record}/edit'),
        ];
    }
}
