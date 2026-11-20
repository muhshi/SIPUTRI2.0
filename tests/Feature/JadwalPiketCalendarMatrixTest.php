<?php

namespace Tests\Feature;

use App\Filament\Widgets\JadwalPiketCalendarWidget;
use App\Models\JadwalPiket;
use App\Models\PegawaiPst;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class JadwalPiketCalendarMatrixTest extends TestCase
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

    public function test_calendar_widget_add_jadwal_action_saves_matrix_slots(): void
    {
        $officers = [];
        for ($i = 1; $i <= 4; $i++) {
            $officers[] = PegawaiPst::create([
                'nama_pegawai' => "Petugas {$i}",
                'nip' => "1990010120200{$i}300{$i}",
            ]);
        }

        $date = '2026-11-24';

        Livewire::test(JadwalPiketCalendarWidget::class)
            ->callAction('addJadwal', [
                'tanggal' => $date,
                'pagi_pelayanan_id' => $officers[0]->id,
                'pagi_pengaduan_id' => $officers[1]->id,
                'siang_pelayanan_id' => $officers[2]->id,
                'siang_pengaduan_id' => $officers[3]->id,
                'send_whatsapp' => false,
            ], ['tanggal' => $date]);

        $this->assertDatabaseHas('jadwal_pikets', [
            'tanggal' => '2026-11-24 00:00:00',
            'pegawai_pst_id' => $officers[0]->id,
            'shift' => 'Pagi',
            'jenis_piket' => 'Pelayanan',
        ]);

        $this->assertDatabaseHas('jadwal_pikets', [
            'tanggal' => '2026-11-24 00:00:00',
            'pegawai_pst_id' => $officers[3]->id,
            'shift' => 'Siang',
            'jenis_piket' => 'Pengaduan/Disabilitas',
        ]);
    }

    public function test_calendar_widget_has_generate_rotation_action(): void
    {
        $officerIds = [];
        for ($i = 1; $i <= 5; $i++) {
            $p = PegawaiPst::create([
                'nama_pegawai' => "Petugas Rotasi {$i}",
                'nip' => "1990010120200{$i}400{$i}",
            ]);
            $officerIds[] = $p->id;
        }

        Livewire::test(JadwalPiketCalendarWidget::class)
            ->callAction('generateRotation', [
                'bulan' => 11,
                'tahun' => 2026,
                'officer_ids' => $officerIds,
                'overwrite_existing' => true,
            ]);

        $count = JadwalPiket::whereYear('tanggal', 2026)->whereMonth('tanggal', 11)->count();
        $this->assertGreaterThan(0, $count);
    }
}
