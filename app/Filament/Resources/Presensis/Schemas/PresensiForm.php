<?php

namespace App\Filament\Resources\Presensis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class PresensiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Presensi')
                    ->description('Silakan isi data kehadiran pegawai.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                \Filament\Forms\Components\Select::make('pegawai_id')
                                    ->label('Pegawai')
                                    ->options(\App\Models\PegawaiPst::all()->pluck('nama_pegawai', 'id'))
                                    ->searchable()
                                    ->required(),
                                DatePicker::make('tanggal')
                                    ->label('Tanggal Presensi')
                                    ->default(now())
                                    ->native(false)
                                    ->required(),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TimePicker::make('jam_masuk')
                                    ->label('Jam Masuk'),
                                TimePicker::make('jam_selesai')
                                    ->label('Jam Pulang'),
                                Select::make('status')
                                    ->options([
                                        'Hadir' => 'Hadir',
                                        'Izin' => 'Izin',
                                        'Sakit' => 'Sakit',
                                        'Alfa' => 'Alfa',
                                    ])
                                    ->default('Hadir')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Lampiran Foto (Opsional)')
                    ->description('Unggah foto bukti jika diperlukan.')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('foto_masuk')
                                    ->label('Foto Masuk')
                                    ->image()
                                    ->extraInputAttributes(['capture' => 'user'])
                                    ->directory('presensi-foto')
                                    ->disk('public'),
                                FileUpload::make('foto_keluar')
                                    ->label('Foto Keluar')
                                    ->image()
                                    ->extraInputAttributes(['capture' => 'user'])
                                    ->directory('presensi-foto')
                                    ->disk('public'),
                            ]),
                    ]),
            ]);
    }
}
