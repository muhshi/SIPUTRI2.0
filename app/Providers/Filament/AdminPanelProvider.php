<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandName('BPS DEMAK') // GANTI DI SINI
            ->favicon('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="gray"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>')
            ->sidebarCollapsibleOnDesktop()
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->darkMode(false)
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->navigationGroups([
                'Layanan & Kunjungan',
                'Manajemen',
                'Pelindung',
            ])
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->renderHook(
                'panels::head.end',
                fn(): string => '
                <style>
                    /* 1. BACKGROUND & GLOBAL UI */
                    .fi-body {
                        background: radial-gradient(circle at top left, #eef2ff 0%, #f8fafc 40%, #ffffff 100%) !important;
                        background-attachment: fixed !important;
                    }

                    /* 2. FORCE SYMMETRY - Mengunci 3 Kolom & Tinggi Sama */
                    .fi-wi-widgets-ctn {
                        display: grid !important;
                        gap: 1.5rem !important;
                        align-items: stretch !important;
                    }

                    /* 3. CARD REFINEMENT - Sangat Bulat & Clean */
                    .fi-wi-stats-overview-stat, 
                    .fi-section, 
                    .fi-widget {
                        background: white !important;
                        border-radius: 28px !important; 
                        border: none !important;
                        box-shadow: 0 15px 35px -12px rgba(0, 0, 0, 0.05) !important;
                        height: 100% !important;
                        display: flex !important;
                        flex-direction: column !important;
                        overflow: hidden !important;
                    }

                    /* Memastikan konten di dalam card (termasuk grafik) mengisi ruang yang ada */
                    .fi-section-content, .fi-widget > div {
                        flex-grow: 1 !important;
                        display: flex !important;
                        flex-direction: column !important;
                        justify-content: center !important;
                    }

                    /* 4. CHART CANVAS - Menjaga Proporsi */
                    canvas {
                        width: 100% !important;
                        /* Memberikan aspek rasio agar grafik tidak lonjong atau terlalu gepeng */
                        aspect-ratio: 4 / 3 !important; 
                        max-height: 250px !important;
                        padding: 10px !important;
                    }

                    /* 5. STATS VALUE - Ukuran Extra Bold */
                    .fi-wi-stats-overview-stat-value {
                        font-size: 3.5rem !important;
                        font-weight: 900 !important;
                        letter-spacing: -0.05em !important;
                        color: #1e293b !important;
                        line-height: 1 !important;
                    }

                    /* 6. SIDEBAR - Glassmorphism */
                    aside.fi-sidebar {
                        background: rgba(255, 255, 255, 0.7) !important;
                        backdrop-filter: blur(10px) !important;
                        border-right: 1px solid rgba(0, 0, 0, 0.02) !important;
                    }
                </style>',
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): string => Blade::render('@include("auth.sso-button")'),
            )
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
