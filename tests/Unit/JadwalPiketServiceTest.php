<?php

namespace Tests\Unit;

use App\Models\JadwalPiket;
use App\Models\PegawaiPst;
use App\Services\JadwalPiketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalPiketServiceTest extends TestCase
{
    use RefreshDatabase;

    protected JadwalPiketService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new JadwalPiketService();
    }

    public function test_can_save_and_retrieve_daily_slots(): void
    {
        $officers = [];
        for ($i = 1; $i <= 4; $i++) {
            $officers[] = PegawaiPst::create([
                'nama_pegawai' => "Petugas {$i}",
                'nip' => "1990010120200{$i}100{$i}",
            ]);
        }

        $date = '2026-11-23';
        $slots = [
            'pagi_pelayanan_id' => $officers[0]->id,
            'pagi_pengaduan_id' => $officers[1]->id,
            'siang_pelayanan_id' => $officers[2]->id,
            'siang_pengaduan_id' => $officers[3]->id,
        ];

        $this->service->saveDailySlots($date, $slots);

        $retrieved = $this->service->getDailySlots($date);

        $this->assertEquals($officers[0]->id, $retrieved['pagi_pelayanan_id']);
        $this->assertEquals($officers[1]->id, $retrieved['pagi_pengaduan_id']);
        $this->assertEquals($officers[2]->id, $retrieved['siang_pelayanan_id']);
        $this->assertEquals($officers[3]->id, $retrieved['siang_pengaduan_id']);
    }

    public function test_prevents_duplicate_officer_on_same_day(): void
    {
        $officer = PegawaiPst::create([
            'nama_pegawai' => 'Petugas Rangkap',
            'nip' => '199001012020011099',
        ]);

        $this->expectException(\InvalidArgumentException::class);

        $this->service->saveDailySlots('2026-11-23', [
            'pagi_pelayanan_id' => $officer->id,
            'pagi_pengaduan_id' => $officer->id, // Duplicate!
            'siang_pelayanan_id' => null,
            'siang_pengaduan_id' => null,
        ]);
    }

    public function test_can_clear_slot_by_setting_null(): void
    {
        $officer = PegawaiPst::create([
            'nama_pegawai' => 'Petugas Solo',
            'nip' => '199001012020011088',
        ]);

        $date = '2026-11-23';
        $this->service->saveDailySlots($date, [
            'pagi_pelayanan_id' => $officer->id,
            'pagi_pengaduan_id' => null,
            'siang_pelayanan_id' => null,
            'siang_pengaduan_id' => null,
        ]);

        $this->assertDatabaseHas('jadwal_pikets', [
            'tanggal' => '2026-11-23 00:00:00',
            'shift' => 'Pagi',
            'jenis_piket' => 'Pelayanan',
        ]);

        // Clear slot
        $this->service->saveDailySlots($date, [
            'pagi_pelayanan_id' => null,
            'pagi_pengaduan_id' => null,
            'siang_pelayanan_id' => null,
            'siang_pengaduan_id' => null,
        ]);

        $this->assertDatabaseMissing('jadwal_pikets', [
            'tanggal' => '2026-11-23 00:00:00',
            'shift' => 'Pagi',
            'jenis_piket' => 'Pelayanan',
        ]);
    }

    public function test_can_generate_monthly_rotation_skipping_weekends(): void
    {
        $officerIds = [];
        for ($i = 1; $i <= 6; $i++) {
            $p = PegawaiPst::create([
                'nama_pegawai' => "Petugas {$i}",
                'nip' => "1990010120200{$i}200{$i}",
            ]);
            $officerIds[] = $p->id;
        }

        // Generate for November 2026
        $daysCount = $this->service->generateMonthlyRotation(2026, 11, $officerIds, true);

        // November 2026 has 21 weekdays (Mon-Fri)
        $this->assertGreaterThanOrEqual(20, $daysCount);

        // Ensure no weekends were scheduled
        $saturdaysAndSundays = JadwalPiket::whereYear('tanggal', 2026)
            ->whereMonth('tanggal', 11)
            ->get()
            ->filter(fn($j) => in_array($j->tanggal->dayOfWeek, [\Carbon\Carbon::SATURDAY, \Carbon\Carbon::SUNDAY]));

        $this->assertCount(0, $saturdaysAndSundays);
    }
}
