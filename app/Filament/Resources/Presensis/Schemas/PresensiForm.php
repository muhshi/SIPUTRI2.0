<?php

namespace App\Filament\Resources\Presensis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class PresensiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('pegawai_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                TimePicker::make('jam_masuk'),
                TimePicker::make('jam_selesai'),
                Select::make('status')
                    ->options(['Hadir' => 'Hadir'])
                    ->default('Hadir')
                    ->required(),
            ]);
    }
}
