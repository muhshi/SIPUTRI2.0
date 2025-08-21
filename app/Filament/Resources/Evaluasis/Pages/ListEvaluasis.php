<?php

namespace App\Filament\Resources\Evaluasis\Pages;

use App\Filament\Resources\Evaluasis\EvaluasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEvaluasis extends ListRecords
{
    protected static string $resource = EvaluasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
