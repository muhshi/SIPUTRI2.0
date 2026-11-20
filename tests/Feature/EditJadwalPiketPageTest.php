<?php

namespace Tests\Feature;

use App\Filament\Resources\JadwalPikets\Pages\EditJadwalPiket;
use App\Models\JadwalPiket;
use App\Models\PegawaiPst;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditJadwalPiketPageTest extends TestCase
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

    public function test_can_load_and_update_matrix_from_edit_page(): void
    {
        $officerA = PegawaiPst::create(['nama_pegawai' => 'Petugas A', 'nip' => '111']);
        $officerB = PegawaiPst::create(['nama_pegawai' => 'Petugas B', 'nip' => '222']);
        $officerC = PegawaiPst::create(['nama_pegawai' => 'Petugas C', 'nip' => '333']);

        $date = '2026-11-26';

        $piket = JadwalPiket::create([
            'tanggal' => $date,
            'pegawai_pst_id' => $officerA->id,
            'shift' => 'Pagi',
            'jenis_piket' => 'Pelayanan',
        ]);

        Livewire::test(EditJadwalPiket::class, ['record' => $piket->getKey()])
            ->assertSchemaStateSet([
                'pagi_pelayanan_id' => $officerA->id,
                'pagi_pengaduan_id' => null,
            ])
            ->fillForm([
                'pagi_pengaduan_id' => $officerB->id,
                'siang_pelayanan_id' => $officerC->id,
                'send_whatsapp' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('jadwal_pikets', [
            'tanggal' => '2026-11-26 00:00:00',
            'pegawai_pst_id' => $officerA->id,
            'shift' => 'Pagi',
            'jenis_piket' => 'Pelayanan',
        ]);

        $this->assertDatabaseHas('jadwal_pikets', [
            'tanggal' => '2026-11-26 00:00:00',
            'pegawai_pst_id' => $officerB->id,
            'shift' => 'Pagi',
            'jenis_piket' => 'Pengaduan/Disabilitas',
        ]);

        $this->assertDatabaseHas('jadwal_pikets', [
            'tanggal' => '2026-11-26 00:00:00',
            'pegawai_pst_id' => $officerC->id,
            'shift' => 'Siang',
            'jenis_piket' => 'Pelayanan',
        ]);
    }
}
