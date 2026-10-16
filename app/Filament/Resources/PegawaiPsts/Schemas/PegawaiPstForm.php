<?php

namespace App\Filament\Resources\PegawaiPsts\Schemas;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PegawaiPstForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Pegawai')
                    ->description('Pilih akun pengguna untuk mengisi data pegawai secara otomatis, lalu unggah foto petugas PST.')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pilih Pegawai (Pengguna)')
                            ->placeholder('Pilih dari daftar akun pengguna...')
                            ->relationship('user', 'name')
                            ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->name}" . ($record->nip ? " (NIP: {$record->nip})" : '') . " - {$record->email}")
                            ->searchable(['name', 'email', 'nip'])
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $user = User::find($state);
                                    if ($user) {
                                        $set('nama_pegawai', $user->name);
                                        $set('nip', $user->nip);
                                        $set('jabatan', $user->jabatan);
                                    }
                                }
                            })
                            ->helperText('Pilih nama pegawai dari data pengguna yang sudah terdaftar.')
                            ->columnSpanFull(),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('nama_pegawai')
                                    ->label('Nama Pegawai')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('nip')
                                    ->label('NIP (18 Digit)')
                                    ->maxLength(255),

                                TextInput::make('jabatan')
                                    ->label('Jabatan')
                                    ->maxLength(255),
                            ]),

                        TextInput::make('no_hp')
                            ->label('No. HP / WhatsApp')
                            ->placeholder('Contoh: 08123456789 atau 628123456789')
                            ->helperText('Nomor ini digunakan untuk pengiriman notifikasi jadwal piket via WhatsApp.')
                            ->tel()
                            ->maxLength(20)
                            ->nullable()
                            ->columnSpanFull(),

                        FileUpload::make('foto_pegawai')
                            ->label('Foto Pegawai')
                            ->helperText('Unggah foto resmi petugas PST (Format: JPG, PNG, WEBP, Maks: 5MB).')
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
                    ]),

                Section::make('Data Tambahan (Opsional)')
                    ->description('Informasi tambahan kepegawaian jika diperlukan.')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('nip_bps')
                                    ->label('NIP BPS (9 Digit)')
                                    ->maxLength(255),

                                TextInput::make('pangkat')
                                    ->label('Pangkat')
                                    ->maxLength(255),

                                TextInput::make('golongan')
                                    ->label('Golongan')
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }
}
