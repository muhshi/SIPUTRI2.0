<?php

namespace App\Filament\Resources\PegawaiPsts\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;

class PegawaiPstForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nip_bps')
                    ->label('NIP BPS')
                    ->maxLength(255),
                TextInput::make('nip')
                    ->label('NIP')
                    ->maxLength(255),
                TextInput::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->required()
                    ->maxLength(255),
                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->maxLength(255),
                TextInput::make('pangkat')
                    ->label('Pangkat')
                    ->maxLength(255),
                TextInput::make('golongan')
                    ->label('Golongan')
                    ->maxLength(255),
                FileUpload::make('foto_pegawai')
                    ->label('Foto Pegawai')
                    ->image()
                    ->disk('public')
                    ->directory('pegawai-pst-photos')
                    ->maxSize(5120) // 5 MB
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth('800')
                    ->imageResizeTargetHeight('800')
                    ->imageEditor()
                    ->nullable()
                    ->columnSpanFull(),

            ]);
    }
}
