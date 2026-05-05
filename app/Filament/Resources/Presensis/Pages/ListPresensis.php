<?php

namespace App\Filament\Resources\Presensis\Pages;

use App\Filament\Resources\Presensis\PresensiResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListPresensis extends ListRecords
{
    protected static string $resource = PresensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('buat_presensi')
                ->label('Buat presensi')
                ->url('/presensi?mode=manual')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }
}
