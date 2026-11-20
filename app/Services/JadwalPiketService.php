<?php

namespace App\Services;

use App\Models\JadwalPiket;
use App\Models\PegawaiPst;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class JadwalPiketService
{
    /**
     * Definisi 4 peran piket standar harian.
     */
    public const ROLE_CONFIG = [
        'pagi_pelayanan_id' => [
            'shift' => 'Pagi',
            'jenis_piket' => 'Pelayanan',
            'label' => 'Shift Pagi - Pelayanan',
        ],
        'pagi_pengaduan_id' => [
            'shift' => 'Pagi',
            'jenis_piket' => 'Pengaduan/Disabilitas',
            'label' => 'Shift Pagi - Pengaduan/Disabilitas',
        ],
        'siang_pelayanan_id' => [
            'shift' => 'Siang',
            'jenis_piket' => 'Pelayanan',
            'label' => 'Shift Siang - Pelayanan',
        ],
        'siang_pengaduan_id' => [
            'shift' => 'Siang',
            'jenis_piket' => 'Pengaduan/Disabilitas',
            'label' => 'Shift Siang - Pengaduan/Disabilitas',
        ],
    ];

    /**
     * Mengambil 4 slot petugas untuk tanggal tertentu.
     */
    public function getDailySlots(string|\DateTimeInterface $date): array
    {
        $dateStr = $date instanceof \DateTimeInterface
            ? Carbon::instance($date)->timezone(config('app.timezone'))->format('Y-m-d')
            : Carbon::parse($date)->timezone(config('app.timezone'))->format('Y-m-d');

        $existing = JadwalPiket::whereDate('tanggal', $dateStr)->get();

        $slots = [];
        foreach (self::ROLE_CONFIG as $slotKey => $config) {
            $record = $existing->first(function ($item) use ($config) {
                return $item->shift === $config['shift'] && $item->jenis_piket === $config['jenis_piket'];
            });
            $slots[$slotKey] = $record?->pegawai_pst_id;
        }

        return $slots;
    }

    /**
     * Menyimpan/memperbarui 4 slot petugas untuk tanggal tertentu.
     * Mencegah duplikasi petugas di hari yang sama.
     */
    public function saveDailySlots(string|\DateTimeInterface $date, array $slots, bool $sendWhatsApp = false): array
    {
        $dateStr = $date instanceof \DateTimeInterface
            ? Carbon::instance($date)->timezone(config('app.timezone'))->format('Y-m-d')
            : Carbon::parse($date)->timezone(config('app.timezone'))->format('Y-m-d');

        // Ambil ID yang terisi
        $assignedIds = [];
        foreach (array_keys(self::ROLE_CONFIG) as $key) {
            if (!empty($slots[$key])) {
                $assignedIds[$key] = (int) $slots[$key];
            }
        }

        // Cek duplikasi
        if (count($assignedIds) !== count(array_unique($assignedIds))) {
            throw new \InvalidArgumentException('Petugas yang sama tidak boleh dijadwalkan lebih dari satu posisi di hari yang sama.');
        }

        $results = [];

        foreach (self::ROLE_CONFIG as $slotKey => $config) {
            $officerId = $assignedIds[$slotKey] ?? null;

            if ($officerId) {
                $jadwal = JadwalPiket::updateOrCreate(
                    [
                        'tanggal' => $dateStr,
                        'shift' => $config['shift'],
                        'jenis_piket' => $config['jenis_piket'],
                    ],
                    [
                        'pegawai_pst_id' => $officerId,
                    ]
                );

                if ($sendWhatsApp && !$jadwal->is_notified) {
                    $this->sendNotification($jadwal);
                }

                $results[$slotKey] = $jadwal;
            } else {
                // Jika slot dikosongkan, hapus jadwal lama jika ada
                JadwalPiket::whereDate('tanggal', $dateStr)
                    ->where('shift', $config['shift'])
                    ->where('jenis_piket', $config['jenis_piket'])
                    ->delete();

                $results[$slotKey] = null;
            }
        }

        return $results;
    }

    /**
     * Membuat rotasi otomatis untuk hari kerja (Senin - Jumat) dalam satu bulan penuh.
     */
    public function generateMonthlyRotation(int $year, int $month, array $officerIds, bool $overwriteExisting = false): int
    {
        $officerIds = array_values(array_unique(array_filter(array_map('intval', $officerIds))));

        if (count($officerIds) < 4) {
            throw new \InvalidArgumentException('Dibutuhkan minimal 4 petugas untuk rotasi piket harian.');
        }

        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $officerCount = count($officerIds);
        $pointer = 0;
        $scheduledDays = 0;

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Lewati hari Sabtu dan Minggu
            if ($date->isWeekend()) {
                continue;
            }

            $dateString = $date->format('Y-m-d');
            $existing = JadwalPiket::whereDate('tanggal', $dateString)->get();

            // Jika tidak overwrite dan hari ini sudah terisi 4 slot penuh, lewati
            if (!$overwriteExisting && $existing->count() >= 4) {
                continue;
            }

            // Ambil 4 petugas unik untuk hari ini secara bergiliran (round-robin)
            $dailyOfficers = [];
            for ($i = 0; $i < 4; $i++) {
                $dailyOfficers[] = $officerIds[($pointer + $i) % $officerCount];
            }
            $pointer = ($pointer + 4) % $officerCount;

            $slots = [
                'pagi_pelayanan_id' => $dailyOfficers[0],
                'pagi_pengaduan_id' => $dailyOfficers[1],
                'siang_pelayanan_id' => $dailyOfficers[2],
                'siang_pengaduan_id' => $dailyOfficers[3],
            ];

            $this->saveDailySlots($dateString, $slots, false);
            $scheduledDays++;
        }

        return $scheduledDays;
    }

    /**
     * Kirim pesan notifikasi WA via OpenWA jika nomor HP tersedia.
     */
    protected function sendNotification(JadwalPiket $jadwal): void
    {
        $pegawai = $jadwal->pegawaiPst;
        if (!$pegawai || !$pegawai->no_hp) {
            return;
        }

        $no_hp = preg_replace('/[^0-9]/', '', $pegawai->no_hp);
        if (str_starts_with($no_hp, '0')) {
            $no_hp = '62' . substr($no_hp, 1);
        }
        if (!str_ends_with($no_hp, '@c.us')) {
            $no_hp .= '@c.us';
        }

        $tanggalFormat = Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y');
        $pesan = "Halo *{$pegawai->nama_pegawai}*,\n\nAnda telah dijadwalkan untuk piket pada:\n📅 Tanggal: {$tanggalFormat}\n🏢 Bagian: {$jadwal->jenis_piket}\n⏰ Shift: {$jadwal->shift}\n\nTerima kasih.\n_Sistem SIPUTRI2.0_";

        try {
            $apiUrl = env('OPENWA_API_URL', 'http://localhost:3000');
            $sessionId = env('OPENWA_SESSION_ID', 'bot-piket');
            $apiKey = env('OPENWA_API_KEY');

            $req = Http::timeout(5);
            if ($apiKey) {
                $req->withHeaders(['X-API-Key' => $apiKey]);
            }

            $response = $req->post("{$apiUrl}/api/sessions/{$sessionId}/messages/send-text", [
                'chatId' => $no_hp,
                'text' => $pesan,
            ]);

            if ($response->successful()) {
                $jadwal->update(['is_notified' => true]);
            }
        } catch (\Throwable $e) {
            // Log or ignore network error for WA
        }
    }
}
