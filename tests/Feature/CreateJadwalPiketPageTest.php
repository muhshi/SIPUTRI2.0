<?php

namespace Tests\Feature;

use App\Filament\Resources\JadwalPikets\Pages\CreateJadwalPiket;
use App\Models\JadwalPiket;
use App\Models\PegawaiPst;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateJadwalPiketPageTest extends TestCase
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

    public function test_can_create_daily_matrix_schedule_from_create_page(): void
    {
        $officers = [];
        for ($i = 1; $i <= 4; $i++) {
            $officers[] = PegawaiPst::create([
                'nama_pegawai' => "Petugas Form {$i}",
                'nip' => "1990010120200{$i}500{$i}",
            ]);
        }

        $date = '2026-11-25';

        Livewire::test(CreateJadwalPiket::class)
            ->fillForm([
                'tanggal' => $date,
                'pagi_pelayanan_id' => $officers[0]->id,
                'pagi_pengaduan_id' => $officers[1]->id,
                'siang_pelayanan_id' => $officers[2]->id,
                'siang_pengaduan_id' => $officers[3]->id,
                'send_whatsapp' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('jadwal_pikets', [
            'tanggal' => '2026-11-25 00:00:00',
            'pegawai_pst_id' => $officers[0]->id,
            'shift' => 'Pagi',
            'jenis_piket' => 'Pelayanan',
        ]);

        $this->assertDatabaseHas('jadwal_pikets', [
            'tanggal' => '2026-11-25 00:00:00',
            'pegawai_pst_id' => $officers[2]->id,
            'shift' => 'Siang',
            'jenis_piket' => 'Pelayanan',
        ]);
    }
}
