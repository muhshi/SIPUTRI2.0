<?php

namespace App\Filament\Resources\JadwalPikets\Schemas;

use App\Models\PegawaiPst;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class JadwalPiketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal')
                    ->label('Tanggal Piket')
                    ->required()
                    ->default(now()),

                \Filament\Schemas\Components\Grid::make(2)
                    ->schema([
                        \Filament\Schemas\Components\Section::make('☀️ Shift Pagi (08.00 - 12.00)')
                            ->description('Piket pelayanan dan pengaduan pagi')
                            ->schema([
                                Select::make('pagi_pelayanan_id')
                                    ->label('Petugas Pelayanan')
                                    ->options(fn () => PegawaiPst::orderBy('nama_pegawai')->pluck('nama_pegawai', 'id'))
                                    ->searchable()
                                    ->placeholder('-- Pilih Petugas Pelayanan --'),
                                Select::make('pagi_pengaduan_id')
                                    ->label('Petugas Pengaduan / Disabilitas')
                                    ->options(fn () => PegawaiPst::orderBy('nama_pegawai')->pluck('nama_pegawai', 'id'))
                                    ->searchable()
                                    ->placeholder('-- Pilih Petugas Pengaduan --'),
                            ]),

                        \Filament\Schemas\Components\Section::make('🌤️ Shift Siang (12.00 - 15.30)')
                            ->description('Piket pelayanan dan pengaduan siang')
                            ->schema([
                                Select::make('siang_pelayanan_id')
                                    ->label('Petugas Pelayanan')
                                    ->options(fn () => PegawaiPst::orderBy('nama_pegawai')->pluck('nama_pegawai', 'id'))
                                    ->searchable()
                                    ->placeholder('-- Pilih Petugas Pelayanan --'),
                                Select::make('siang_pengaduan_id')
                                    ->label('Petugas Pengaduan / Disabilitas')
                                    ->options(fn () => PegawaiPst::orderBy('nama_pegawai')->pluck('nama_pegawai', 'id'))
                                    ->searchable()
                                    ->placeholder('-- Pilih Petugas Pengaduan --'),
                            ]),
                    ]),

                Toggle::make('send_whatsapp')
                    ->label('Kirim Notifikasi WhatsApp ke Petugas Baru')
                    ->default(true),
            ]);
    }
}
