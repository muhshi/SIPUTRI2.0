<?php

namespace App\Filament\Resources\Evaluasis\Pages;

use App\Filament\Resources\Evaluasis\EvaluasiResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEvaluasi extends ViewRecord
{
    protected static string $resource = EvaluasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
