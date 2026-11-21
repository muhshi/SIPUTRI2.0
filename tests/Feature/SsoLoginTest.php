<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\Login;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SsoLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_login_page_renders_hero_sso_button_and_fallback(): void
    {
        Livewire::test(Login::class)
            ->assertSee('Masuk dengan SIPETRA SSO')
            ->assertSee('Opsi Darurat / Pengembang')
            ->assertSee('images/logo_bps.png')
            ->assertSet('showFallback', false);
    }

    public function test_login_page_activates_fallback_with_query_param(): void
    {
        Livewire::withQueryParams(['fallback' => '1'])
            ->test(Login::class)
            ->assertSet('showFallback', true);
    }

    public function test_sso_callback_with_error_redirects_to_filament_login(): void
    {
        $response = $this->get('/auth/sipetra/callback?error=access_denied&error_description=Dibatalkan');

        $response->assertRedirect(route('filament.admin.auth.login'));
        $response->assertSessionHas('error', 'Dibatalkan');
    }
}
