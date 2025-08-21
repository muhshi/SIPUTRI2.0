<?php

namespace App\Filament\Resources\Kunjungans\Pages;

use App\Filament\Resources\Kunjungans\KunjunganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKunjungans extends ListRecords
{
    protected static string $resource = KunjunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
