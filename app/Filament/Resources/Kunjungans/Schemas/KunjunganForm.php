<?php

namespace App\Filament\Resources\Kunjungans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class KunjunganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->label('Nama Pengunjung')
                    ->required(),

                Select::make('pegawai_id')
                    ->label('Pegawai yang Melayani')
                    ->relationship('pegawai', 'nama_pegawai')
                    ->searchable()
                    ->preload(),

                DatePicker::make('tanggal')
                    ->label('Tanggal Kunjungan')
                    ->default(now())
                    ->required(),

                DateTimePicker::make('waktu_mulai')
                    ->label('Waktu Mulai Layanan')
                    ->seconds(false),

                DateTimePicker::make('waktu_selesai')
                    ->label('Waktu Selesai Layanan')
                    ->seconds(false),

                TextInput::make('pendidikan')
                    ->label('Pendidikan'),

                TextInput::make('pekerjaan')
                    ->label('Pekerjaan'),

                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),

                TextInput::make('instansi')
                    ->label('Instansi'),

                TextInput::make('email')
                    ->label('Email')
                    ->email(),

                TextInput::make('pemanfaatan')
                    ->label('Pemanfaatan Data'),

                TextInput::make('tahun_lahir')
                    ->label('Tahun Lahir')
                    ->numeric(),

                TextInput::make('layanan')
                    ->label('Layanan'),

                Select::make('status')
                    ->label('Status Layanan')
                    ->options([
                        'selesai' => 'Selesai',
                        'proses' => 'Proses',
                        'batal' => 'Batal',
                    ])
                    ->default('selesai')
                    ->required(),

                TextInput::make('umur')
                    ->label('Umur')
                    ->numeric(),

                TextInput::make('data_diinginkan')
                    ->label('Data Diinginkan'),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->columnSpanFull(),
            ]);
    }
}
