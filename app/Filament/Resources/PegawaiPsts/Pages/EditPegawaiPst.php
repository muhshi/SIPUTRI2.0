<?php

namespace App\Filament\Resources\PegawaiPsts\Pages;

use App\Filament\Resources\PegawaiPsts\PegawaiPstResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPegawaiPst extends EditRecord
{
    protected static string $resource = PegawaiPstResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
