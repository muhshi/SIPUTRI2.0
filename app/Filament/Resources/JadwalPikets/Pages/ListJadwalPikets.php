<?php

namespace App\Filament\Resources\JadwalPikets\Pages;

use App\Filament\Resources\JadwalPikets\JadwalPiketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJadwalPikets extends ListRecords
{
    protected static string $resource = JadwalPiketResource::class;
    
    protected string $view = 'filament.resources.jadwal-pikets.pages.calendar-only';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
