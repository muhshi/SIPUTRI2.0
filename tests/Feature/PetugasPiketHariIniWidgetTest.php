<?php

namespace Tests\Feature;

use App\Filament\Widgets\PetugasPiketHariIniWidget;
use App\Models\JadwalPiket;
use App\Models\PegawaiPst;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PetugasPiketHariIniWidgetTest extends TestCase
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

    public function test_widget_displays_empty_state_when_no_piket(): void
    {
        Livewire::test(PetugasPiketHariIniWidget::class)
            ->assertSee('Belum ada jadwal piket yang ditetapkan untuk hari ini');
    }

    public function test_widget_displays_piket_with_attendance_status(): void
    {
        $pegawai1 = PegawaiPst::create([
            'nama_pegawai' => 'Petugas Pelayanan',
            'nip' => '199101012020011001',
        ]);
        $pegawai2 = PegawaiPst::create([
            'nama_pegawai' => 'Petugas Pengaduan',
            'nip' => '199201012020011002',
        ]);

        JadwalPiket::create([
            'pegawai_pst_id' => $pegawai1->id,
            'tanggal' => now()->toDateString(),
            'jenis_piket' => 'Pelayanan',
            'shift' => 'Pagi',
        ]);

        JadwalPiket::create([
            'pegawai_pst_id' => $pegawai2->id,
            'tanggal' => now()->toDateString(),
            'jenis_piket' => 'Pengaduan/Disabilitas',
            'shift' => 'Siang',
        ]);

        // Pegawai 1 has checked in
        Presensi::create([
            'pegawai_id' => $pegawai1->id,
            'tanggal' => now()->toDateString(),
            'jam_masuk' => '07:50:00',
            'status' => 'Hadir',
        ]);

        Livewire::test(PetugasPiketHariIniWidget::class)
            ->assertSee('Petugas Pelayanan')
            ->assertSee('Petugas Pengaduan')
            ->assertSee('Hadir (Masuk: 07:50)')
            ->assertSee('Belum Presensi');
    }
}
