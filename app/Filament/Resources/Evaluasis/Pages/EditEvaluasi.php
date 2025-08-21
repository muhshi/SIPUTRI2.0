<?php

namespace App\Filament\Resources\Evaluasis\Pages;

use App\Filament\Resources\Evaluasis\EvaluasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEvaluasi extends EditRecord
{
    protected static string $resource = EvaluasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
