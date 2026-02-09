<?php

namespace App\Filament\Widgets;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Widget;

class DashboardFilterWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.dashboard-filter-widget';

    protected int|string|array $columnSpan = 12;

    protected static ?int $sort = 3;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'year' => now()->year,
            'quarter' => 'q' . ceil(now()->month / 3),
        ]);

        // Dispatch initial state
        $this->dispatch('update-filters', year: now()->year, quarter: 'q' . ceil(now()->month / 3));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Grid::make(2)
                    ->schema([
                        Select::make('year')
                            ->label('')
                            ->options([
                                2024 => '2024',
                                2025 => '2025',
                                2026 => '2026',
                            ])
                            ->default(now()->year)
                            ->native(false)
                            ->selectablePlaceholder(false)
                            ->live()
                            ->afterStateUpdated(fn() => $this->dispatch('update-filters', year: (int) $this->data['year'], quarter: $this->data['quarter']))
                            ->extraAttributes(['class' => 'min-w-[100px]']),

                        Select::make('quarter')
                            ->label('')
                            ->options([
                                'q1' => 'Triwulan 1 (Jan-Mar)',
                                'q2' => 'Triwulan 2 (Apr-Jun)',
                                'q3' => 'Triwulan 3 (Jul-Sep)',
                                'q4' => 'Triwulan 4 (Okt-Des)',
                            ])
                            ->default('q' . ceil(now()->month / 3))
                            ->native(false)
                            ->selectablePlaceholder(false)
                            ->live()
                            ->afterStateUpdated(fn() => $this->dispatch('update-filters', year: (int) $this->data['year'], quarter: $this->data['quarter']))
                            ->extraAttributes(['class' => 'min-w-[200px]']),
                    ]),
            ])
            ->statePath('data');
    }
}
