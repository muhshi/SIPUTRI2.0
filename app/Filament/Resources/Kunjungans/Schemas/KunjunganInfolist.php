<?php

namespace App\Filament\Resources\Kunjungans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KunjunganInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama'),
                TextEntry::make('pendidikan'),
                TextEntry::make('tanggal')
                    ->date(),
                TextEntry::make('pekerjaan'),
                TextEntry::make('jenis_kelamin'),
                TextEntry::make('instansi'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('pemanfaatan'),
                TextEntry::make('tahun_lahir')
                    ->numeric(),
                TextEntry::make('layanan'),
                TextEntry::make('umur')
                    ->numeric(),
                TextEntry::make('data_diinginkan'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
