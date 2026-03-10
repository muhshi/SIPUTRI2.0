<?php

namespace App\Filament\Resources\Evaluasis;

use App\Filament\Resources\Evaluasis\Pages\CreateEvaluasi;
use App\Filament\Resources\Evaluasis\Pages\EditEvaluasi;
use App\Filament\Resources\Evaluasis\Pages\ListEvaluasis;
use App\Filament\Resources\Evaluasis\Schemas\EvaluasiForm;
use App\Filament\Resources\Evaluasis\Tables\EvaluasisTable;
use App\Models\Evaluasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EvaluasiResource extends Resource
{
    protected static ?string $model = Evaluasi::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static string|UnitEnum|null $navigationGroup = 'Layanan & Kunjungan';

    protected static ?string $navigationLabel = 'Evaluasi & Rating';

    public static function form(Schema $schema): Schema
    {
        return EvaluasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EvaluasisTable::configure($table);
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
            'index' => ListEvaluasis::route('/'),
            'create' => CreateEvaluasi::route('/create'),
            'edit' => EditEvaluasi::route('/{record}/edit'),
        ];
    }
}
