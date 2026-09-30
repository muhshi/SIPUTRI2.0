<?php

namespace App\Filament\Widgets;

use App\Models\JadwalPiket;
use Filament\Widgets\Widget;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class JadwalPiketCalendarWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected string $view = 'filament.widgets.jadwal-piket-calendar-widget';
    protected int | string | array $columnSpan = 'full';
    
    protected static bool $isDiscovered = false;

    public $currentMonth;
    public $currentYear;
    public $isEditMode = false;

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function toggleEditMode()
    {
        $this->isEditMode = !$this->isEditMode;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function addJadwalAction(): Action
    {
        return Action::make('addJadwal')
            ->label('Tambah Pegawai')
            ->modalHeading(fn (array $arguments) => 'Tambah Jadwal - ' . Carbon::parse($arguments['tanggal'] ?? now())->translatedFormat('d F Y'))
            ->modalSubmitActionLabel('Simpan')
            ->modalDescription(function (array $arguments) {
                $tanggal = $arguments['tanggal'] ?? now()->format('Y-m-d');
                $existing = JadwalPiket::whereDate('tanggal', $tanggal)->get();
                
                $pelayananTaken = $existing->where('jenis_piket', 'Pelayanan')->count();
                $pengaduanTaken = $existing->where('jenis_piket', 'Pengaduan/Disabilitas')->count();
                
                $pelayananAvail = max(0, 2 - $pelayananTaken);
                $pengaduanAvail = max(0, 2 - $pengaduanTaken);
                
                return new \Illuminate\Support\HtmlString("Sisa Slot pada tanggal ini:<br><strong>Pelayanan:</strong> {$pelayananAvail} slot tersisa<br><strong>Pengaduan/Disabilitas:</strong> {$pengaduanAvail} slot tersisa");
            })
            ->form(function (array $arguments) {
                $tanggal = $arguments['tanggal'] ?? now()->format('Y-m-d');
                $alreadyScheduledIds = JadwalPiket::whereDate('tanggal', $tanggal)->pluck('pegawai_pst_id')->toArray();
                $existing = JadwalPiket::whereDate('tanggal', $tanggal)->get();

                $maxPelayanan = max(0, 2 - $existing->where('jenis_piket', 'Pelayanan')->count());
                $maxPengaduan = max(0, 2 - $existing->where('jenis_piket', 'Pengaduan/Disabilitas')->count());
                $allOptions = \App\Models\PegawaiPst::whereNotIn('id', $alreadyScheduledIds)->pluck('nama_pegawai', 'id');

                return [
                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            \Filament\Schemas\Components\Section::make('🟦 Piket Pelayanan')
                                ->description("Maks. {$maxPelayanan} orang lagi")
                                ->schema([
                                    \Filament\Forms\Components\CheckboxList::make('pegawai_pelayanan')
                                        ->label('')
                                        ->options($allOptions)
                                        ->searchable()
                                        ->live()
                                        ->maxItems($maxPelayanan)
                                        ->disableOptionWhen(function (string|int $value, \Filament\Schemas\Components\Utilities\Get $get) {
                                            return in_array($value, (array) $get('pegawai_pengaduan'));
                                        }),
                                ]),
                            \Filament\Schemas\Components\Section::make('🟩 Piket Pengaduan/Disabilitas')
                                ->description("Maks. {$maxPengaduan} orang lagi")
                                ->schema([
                                    \Filament\Forms\Components\CheckboxList::make('pegawai_pengaduan')
                                        ->label('')
                                        ->options($allOptions)
                                        ->searchable()
                                        ->live()
                                        ->maxItems($maxPengaduan)
                                        ->disableOptionWhen(function (string|int $value, \Filament\Schemas\Components\Utilities\Get $get) {
                                            return in_array($value, (array) $get('pegawai_pelayanan'));
                                        }),
                                ]),
                        ]),
                ];
            })
            ->action(function (array $data, array $arguments) {
                $tanggal = $arguments['tanggal'];
                $existing = JadwalPiket::whereDate('tanggal', $tanggal)->get();
                
                $pelayananIds = $data['pegawai_pelayanan'] ?? [];
                $pengaduanIds = $data['pegawai_pengaduan'] ?? [];
                
                if (empty($pelayananIds) && empty($pengaduanIds)) {
                    Notification::make()->title('Gagal!')->body("Anda belum memilih pegawai sama sekali.")->danger()->send();
                    return;
                }
                
                if (count(array_intersect($pelayananIds, $pengaduanIds)) > 0) {
                    Notification::make()->title('Duplikasi!')->body("Pegawai yang sama tidak boleh diletakkan di dua bagian sekaligus.")->danger()->send();
                    return;
                }
                
                $pelayananTaken = $existing->where('jenis_piket', 'Pelayanan')->count();
                $pengaduanTaken = $existing->where('jenis_piket', 'Pengaduan/Disabilitas')->count();
                
                if (count($pelayananIds) > (2 - $pelayananTaken)) {
                    Notification::make()->title('Gagal!')->body("Kelebihan pegawai di Pelayanan. Sisa slot: " . (2 - $pelayananTaken))->danger()->send();
                    return;
                }
                
                if (count($pengaduanIds) > (2 - $pengaduanTaken)) {
                    Notification::make()->title('Gagal!')->body("Kelebihan pegawai di Pengaduan. Sisa slot: " . (2 - $pengaduanTaken))->danger()->send();
                    return;
                }
                
                $berhasilCount = 0;
                $waTerkirim = 0;
                
                // Proses Pelayanan
                $shiftsPelayanan = ['Pagi', 'Siang'];
                $takenShiftsPelayanan = $existing->where('jenis_piket', 'Pelayanan')->pluck('shift')->toArray();
                $availShiftsPelayanan = array_values(array_diff($shiftsPelayanan, $takenShiftsPelayanan));
                
                foreach ($pelayananIds as $idx => $pegawai_id) {
                    $shift = $availShiftsPelayanan[$idx] ?? 'Pagi';
                    $jadwal = JadwalPiket::create([
                        'tanggal' => $tanggal,
                        'pegawai_pst_id' => $pegawai_id,
                        'jenis_piket' => 'Pelayanan',
                        'shift' => $shift,
                        'is_notified' => false,
                    ]);
                    
                    $pegawai = \App\Models\PegawaiPst::find($pegawai_id);
                    if ($pegawai && $pegawai->no_hp) {
                        $no_hp = preg_replace('/[^0-9]/', '', $pegawai->no_hp);
                        if (str_starts_with($no_hp, '0')) $no_hp = '62' . substr($no_hp, 1);
                        if (!str_ends_with($no_hp, '@c.us')) $no_hp .= '@c.us';
                        
                        $tanggalFormat = Carbon::parse($tanggal)->translatedFormat('l, d F Y');
                        $pesan = "Halo *{$pegawai->nama_pegawai}*,\n\nAnda telah dijadwalkan untuk piket pada:\n📅 Tanggal: {$tanggalFormat}\n🏢 Bagian: Pelayanan\n⏰ Shift: {$shift}\n\nTerima kasih.\n_Sistem SIPUTRI2.0_";
                        try {
                            $response = Http::timeout(5)->withHeaders(['X-API-Key' => env('OPENWA_API_KEY')])
                                ->post(env('OPENWA_API_URL', 'http://localhost:2785') . '/api/sessions/' . env('OPENWA_SESSION_ID') . '/messages/send-text', [
                                    'chatId' => $no_hp, 'text' => $pesan,
                                ]);
                            if ($response->successful()) {
                                $jadwal->update(['is_notified' => true]);
                                $waTerkirim++;
                            }
                        } catch (\Exception $e) {}
                    }
                    $berhasilCount++;
                }
                
                // Proses Pengaduan
                $shiftsPengaduan = ['Pagi', 'Siang'];
                $takenShiftsPengaduan = $existing->where('jenis_piket', 'Pengaduan/Disabilitas')->pluck('shift')->toArray();
                $availShiftsPengaduan = array_values(array_diff($shiftsPengaduan, $takenShiftsPengaduan));
                
                foreach ($pengaduanIds as $idx => $pegawai_id) {
                    $shift = $availShiftsPengaduan[$idx] ?? 'Pagi';
                    $jadwal = JadwalPiket::create([
                        'tanggal' => $tanggal,
                        'pegawai_pst_id' => $pegawai_id,
                        'jenis_piket' => 'Pengaduan/Disabilitas',
                        'shift' => $shift,
                        'is_notified' => false,
                    ]);
                    
                    $pegawai = \App\Models\PegawaiPst::find($pegawai_id);
                    if ($pegawai && $pegawai->no_hp) {
                        $no_hp = preg_replace('/[^0-9]/', '', $pegawai->no_hp);
                        if (str_starts_with($no_hp, '0')) $no_hp = '62' . substr($no_hp, 1);
                        if (!str_ends_with($no_hp, '@c.us')) $no_hp .= '@c.us';
                        
                        $tanggalFormat = Carbon::parse($tanggal)->translatedFormat('l, d F Y');
                        $pesan = "Halo *{$pegawai->nama_pegawai}*,\n\nAnda telah dijadwalkan untuk piket pada:\n📅 Tanggal: {$tanggalFormat}\n🏢 Bagian: Pengaduan/Disabilitas\n⏰ Shift: {$shift}\n\nTerima kasih.\n_Sistem SIPUTRI2.0_";
                        try {
                            $response = Http::timeout(5)->withHeaders(['X-API-Key' => env('OPENWA_API_KEY')])
                                ->post(env('OPENWA_API_URL', 'http://localhost:2785') . '/api/sessions/' . env('OPENWA_SESSION_ID') . '/messages/send-text', [
                                    'chatId' => $no_hp, 'text' => $pesan,
                                ]);
                            if ($response->successful()) {
                                $jadwal->update(['is_notified' => true]);
                                $waTerkirim++;
                            }
                        } catch (\Exception $e) {}
                    }
                    $berhasilCount++;
                }

                Notification::make()
                    ->title('Jadwal Berhasil Ditambahkan!')
                    ->body("{$berhasilCount} pegawai dijadwalkan. ({$waTerkirim} WA terkirim).")
                    ->success()
                    ->send();
            });
    }

    public function deleteJadwalAction(): Action
    {
        return Action::make('deleteJadwal')
            ->label('Hapus')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                JadwalPiket::find($arguments['id'])?->delete();
                Notification::make()
                    ->title('Jadwal Dihapus')
                    ->success()
                    ->send();
            });
    }

    protected function getViewData(): array
    {
        $startOfMonth = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);

        $jadwals = JadwalPiket::with('pegawaiPst')
            ->whereBetween('tanggal', [$startOfCalendar->format('Y-m-d'), $endOfCalendar->format('Y-m-d')])
            ->get()
            ->groupBy(fn ($val) => Carbon::parse($val->tanggal)->format('Y-m-d'));

        $days = [];
        $currentDate = $startOfCalendar->copy();
        
        $orderMap = [
            'Pelayanan-Pagi' => 1,
            'Pengaduan/Disabilitas-Pagi' => 2,
            'Pelayanan-Siang' => 3,
            'Pengaduan/Disabilitas-Siang' => 4,
        ];

        while ($currentDate <= $endOfCalendar) {
            $dateString = $currentDate->format('Y-m-d');
            $hariJadwal = $jadwals->get($dateString, collect())->sortBy(function($item) use ($orderMap) {
                return $orderMap[$item->jenis_piket . '-' . $item->shift] ?? 99;
            })->values();
            
            $days[] = [
                'date' => $currentDate->copy(),
                'dateString' => $dateString,
                'isCurrentMonth' => $currentDate->month === $this->currentMonth,
                'jadwals' => $hariJadwal,
                'isFull' => $hariJadwal->count() >= 4,
            ];
            $currentDate->addDay();
        }

        return [
            'days' => $days,
            'monthName' => $startOfMonth->locale('id')->translatedFormat('F Y'),
        ];
    }
}
