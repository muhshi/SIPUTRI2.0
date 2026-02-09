<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class PresensiShortcut extends Widget
{
    protected string $view = 'filament.widgets.presensi-shortcut';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 4;

    protected static ?string $heading = 'Presensi';


}
