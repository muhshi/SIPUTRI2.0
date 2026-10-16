<?php

namespace App\Filament\Resources\JadwalPikets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class JadwalPiketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('pegawai_pst_id')
                    ->label('Pegawai Piket')
                    ->relationship('pegawaiPst', 'nama_pegawai')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('tanggal')
                    ->label('Tanggal Piket')
                    ->required(),
                \Filament\Forms\Components\Select::make('jenis_piket')
                    ->label('Jenis Piket')
                    ->options([
                        'Pelayanan' => 'Pelayanan',
                        'Pengaduan/Disabilitas' => 'Pengaduan/Disabilitas',
                    ])
                    ->required()
                    ->default('Pelayanan'),
                \Filament\Forms\Components\Select::make('shift')
                    ->label('Shift')
                    ->options([
                        'Pagi' => 'Pagi',
                        'Siang' => 'Siang',
                    ])
                    ->required()
                    ->default('Pagi'),
                Toggle::make('is_notified')
                    ->label('Notifikasi Terkirim?')
                    ->default(false),
            ]);
    }
}
