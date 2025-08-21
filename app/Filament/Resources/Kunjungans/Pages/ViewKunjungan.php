<?php

namespace App\Filament\Resources\Kunjungans\Pages;

use App\Filament\Resources\Kunjungans\KunjunganResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKunjungan extends ViewRecord
{
    protected static string $resource = KunjunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
