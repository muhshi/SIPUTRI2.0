<?php

namespace App\Filament\Resources\Evaluasis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EvaluasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('pegawai_id')
                    ->label('Pegawai')
                    ->relationship('pegawai', 'nama_pegawai')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('pengunjung_id')
                    ->label('Pengunjung')
                    ->relationship('pengunjung', 'nama')
                    ->searchable()
                    ->preload(),

                TextInput::make('rating')
                    ->label('Rating')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5),

                DatePicker::make('tanggal_evaluasi')
                    ->label('Tanggal Evaluasi')
                    ->required()
                    ->default(now())
                    ->native(false)
                    ->displayFormat('d-m-Y'),
            ]);
    }
}
