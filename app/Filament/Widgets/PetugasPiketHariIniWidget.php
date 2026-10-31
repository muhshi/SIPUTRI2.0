<?php

namespace App\Filament\Widgets;

use App\Models\JadwalPiket;
use App\Models\Presensi;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class PetugasPiketHariIniWidget extends Widget
{
    protected string $view = 'filament.widgets.petugas-piket-hari-ini';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 12;

    public function getViewData(): array
    {
        $today = Carbon::today();
        $formattedDate = $today->locale('id')->translatedFormat('l, d F Y');

        $jadwals = JadwalPiket::with('pegawaiPst')
            ->whereDate('tanggal', $today)
            ->orderBy('shift')
            ->get();

        $piketData = $jadwals->map(function ($jadwal) use ($today) {
            $pegawai = $jadwal->pegawaiPst;
            $presensi = null;

            if ($pegawai) {
                $presensi = Presensi::where('pegawai_id', $pegawai->id)
                    ->whereDate('tanggal', $today)
                    ->first();
            }

            if (!$presensi) {
                $statusLabel = 'Belum Presensi';
                $statusColor = 'danger';
                $statusIcon = 'heroicon-m-x-circle';
            } elseif (!$presensi->jam_selesai) {
                $jam = $presensi->jam_masuk ? substr($presensi->jam_masuk, 0, 5) : '-';
                $statusLabel = "Hadir (Masuk: {$jam})";
                $statusColor = 'success';
                $statusIcon = 'heroicon-m-check-circle';
            } else {
                $jam = $presensi->jam_selesai ? substr($presensi->jam_selesai, 0, 5) : '-';
                $statusLabel = "Selesai Piket (Pulang: {$jam})";
                $statusColor = 'info';
                $statusIcon = 'heroicon-m-clock';
            }

            // Jam shift
            $isJumat = $today->dayOfWeek === Carbon::FRIDAY;
            $shiftTime = $jadwal->shift === 'Pagi'
                ? ($isJumat ? '08.00 - 11.30 WIB' : '08.00 - 12.00 WIB')
                : ($isJumat ? '11.30 - 15.30 WIB' : '12.00 - 15.30 WIB');

            return [
                'jadwal' => $jadwal,
                'pegawai' => $pegawai,
                'presensi' => $presensi,
                'status_label' => $statusLabel,
                'status_color' => $statusColor,
                'status_icon' => $statusIcon,
                'shift_time' => $shiftTime,
            ];
        });

        return [
            'formattedDate' => $formattedDate,
            'piketData' => $piketData,
            'totalPetugas' => $piketData->count(),
        ];
    }
}
