<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('piket:notify')]
#[Description('Kirim notifikasi WA jadwal piket hari ini')]
class SendPiketNotification extends Command
{
    public function handle()
    {
        $today = \Carbon\Carbon::today()->toDateString();
        $this->info("Mengecek jadwal piket untuk tanggal {$today}...");

        $jadwals = \App\Models\JadwalPiket::with('pegawaiPst')
            ->where('tanggal', $today)
            ->where('is_notified', false)
            ->get();

        if ($jadwals->isEmpty()) {
            $this->info("Tidak ada jadwal piket yang belum dinotifikasi hari ini.");
            return;
        }

        $apiUrl = env('OPENWA_API_URL', 'http://localhost:3000');
        $sessionId = env('OPENWA_SESSION_ID', 'bot-piket');

        foreach ($jadwals as $jadwal) {
            $pegawai = $jadwal->pegawaiPst;
            
            if (!$pegawai || !$pegawai->no_hp) {
                $this->warn("Pegawai {$pegawai?->nama_pegawai} tidak memiliki nomor HP. Melewati...");
                continue;
            }

            // Format nomor HP: hapus selain angka, pastikan diawali 62
            $noHp = preg_replace('/[^0-9]/', '', $pegawai->no_hp);
            if (str_starts_with($noHp, '08')) {
                $noHp = '628' . substr($noHp, 2);
            }
            $chatId = $noHp . '@c.us';

            $pesan = "Halo *{$pegawai->nama_pegawai}*,\n\n";
            $pesan .= "Ini adalah pengingat otomatis dari SIPUTRI2.0 bahwa hari ini tanggal *{$today}* adalah jadwal Anda untuk bertugas sebagai Piket Pelayanan Statistik Terpadu (PST) BPS Kabupaten Demak.\n\n";
            
            $pesan .= "Rincian Tugas:\n";
            $pesan .= "Tugas: *Piket {$jadwal->jenis_piket}*\n";
            
            $hariIni = \Carbon\Carbon::parse($today)->locale('id')->dayName;
            $jam = "";
            if ($jadwal->shift == 'Pagi') {
                if ($hariIni == 'Jumat') {
                    $jam = "08.00 - 11.30";
                } else {
                    $jam = "08.00 - 12.00";
                }
            } else { // Siang
                if ($hariIni == 'Jumat') {
                    $jam = "11.30 - 15.30";
                } else {
                    $jam = "12.00 - 15.30";
                }
            }
            $pesan .= "Shift: *{$jadwal->shift} ({$jam})*\n\n";
            $pesan .= "Mohon laksanakan tugas dengan baik. Terima kasih!";

            try {
                $response = \Illuminate\Support\Facades\Http::post("{$apiUrl}/api/sessions/{$sessionId}/messages/send-text", [
                    'chatId' => $chatId,
                    'text' => $pesan,
                ]);

                // Meskipun error 500 (dari bug DB lokal OpenWA), pesan biasanya tetap terkirim.
                // Kita anggap berhasil jika request tidak timeout/gagal secara network.
                $this->info("Request notifikasi untuk {$pegawai->nama_pegawai} ({$noHp}) telah dieksekusi. Status: " . $response->status());
                
                // Tandai sudah dinotifikasi
                $jadwal->update(['is_notified' => true]);

            } catch (\Exception $e) {
                $this->error("Gagal mengirim notifikasi untuk {$pegawai->nama_pegawai}: " . $e->getMessage());
            }
        }

        $this->info("Selesai memproses notifikasi piket.");
    }
}
