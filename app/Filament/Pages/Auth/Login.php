<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

if (class_exists(\Filament\Auth\Pages\Login::class) && ! class_exists(\Filament\Pages\Auth\Login::class)) {
    class_alias(\Filament\Auth\Pages\Login::class, \Filament\Pages\Auth\Login::class);
}

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';

    public bool $showFallback = false;

    public function mount(): void
    {
        parent::mount();

        if (request()->query('fallback') === '1' || request()->query('fallback') === 'true') {
            $this->showFallback = true;
        }
    }

    public function toggleFallback(): void
    {
        $this->showFallback = ! $this->showFallback;
    }

    public function getHeading(): string | Htmlable
    {
        return 'Masuk ke SIPUTRI 2.0';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Sistem Informasi Pelayanan Statistik Terpadu BPS Kab. Demak';
    }
}
