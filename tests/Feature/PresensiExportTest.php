<?php

namespace Tests\Feature;

use App\Filament\Resources\Presensis\Pages\ListPresensis;
use App\Models\PegawaiPst;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PresensiExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);
        $this->actingAs($admin);
    }

    public function test_print_presensi_route_returns_success_and_displays_data(): void
    {
        $pegawai = PegawaiPst::create([
            'nama_pegawai' => 'Ahmad Faiz',
            'nip' => '198501012010011005',
        ]);

        Presensi::create([
            'pegawai_id' => $pegawai->id,
            'tanggal' => now()->toDateString(),
            'jam_masuk' => '07:45:00',
            'jam_selesai' => '16:00:00',
            'status' => 'Hadir',
        ]);

        $response = $this->get(route('print.presensi', ['pegawai_id' => $pegawai->id]));
        $response->assertStatus(200);
        $response->assertSee('Laporan Rekap Presensi Pegawai PST');
        $response->assertSee('Ahmad Faiz');
        $response->assertSee('07:45:00');
    }

    public function test_list_presensis_page_has_export_and_print_actions(): void
    {
        $page = new ListPresensis();
        $ref = new \ReflectionClass($page);
        $method = $ref->getMethod('getHeaderActions');
        $method->setAccessible(true);
        $actions = $method->invoke($page);

        $actionNames = array_map(fn($a) => $a->getName(), $actions);
        $this->assertContains('export_xlsx', $actionNames);
        $this->assertContains('print_pdf', $actionNames);
    }
}
