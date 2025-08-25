<?php

namespace App\Filament\Resources\Evaluasis\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class EvaluasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('pegawai_id')
                    ->label('pegawai', 'nama')
                    ->required(),

                TextInput::make('rating')
                    ->required()
                    ->numeric(),
            ]);
    }
}
