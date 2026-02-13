<?php

namespace App\Filament\Resources\PegawaiPsts\Pages;

use App\Filament\Resources\PegawaiPsts\PegawaiPstResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPegawaiPsts extends ListRecords
{
    protected static string $resource = PegawaiPstResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
