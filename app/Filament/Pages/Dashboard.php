<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected string $view = 'filament.pages.dashboard';

    public function getColumns(): int|array
    {
        return 12;
    }
}

