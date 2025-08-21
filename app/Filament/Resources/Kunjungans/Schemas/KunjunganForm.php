<?php

namespace App\Filament\Resources\Kunjungans\Schemas;

use Filament\Forms\Components\DatePicker;
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

                TextInput::make('pendidikan')
                    ->label('Pendidikan'),

                DatePicker::make('tanggal')
                    ->label('Tanggal Kunjungan')
                    ->required(),

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
