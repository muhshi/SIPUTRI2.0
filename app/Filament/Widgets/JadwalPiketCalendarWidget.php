<?php

namespace App\Filament\Widgets;

use App\Models\JadwalPiket;
use App\Models\PegawaiPst;
use App\Services\JadwalPiketService;
use Filament\Widgets\Widget;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;

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
            ->label('Atur Jadwal Piket')
            ->modalHeading(fn (array $arguments) => 'Atur Jadwal Piket - ' . Carbon::parse($arguments['tanggal'] ?? now())->translatedFormat('l, d F Y'))
            ->modalSubmitActionLabel('Simpan Jadwal')
            ->modalDescription('Tentukan petugas untuk masing-masing shift (Pagi & Siang) pada tanggal ini.')
            ->form(function (array $arguments) {
                $tanggal = $arguments['tanggal'] ?? now()->format('Y-m-d');
                $allOptions = PegawaiPst::orderBy('nama_pegawai')->pluck('nama_pegawai', 'id');
                $dailySlots = app(JadwalPiketService::class)->getDailySlots($tanggal);

                return [
                    Hidden::make('tanggal')->default($tanggal),
                    \Filament\Schemas\Components\Grid::make(2)
                        ->schema([
                            \Filament\Schemas\Components\Section::make('☀️ Shift Pagi (08.00 - 12.00)')
                                ->description('Piket pelayanan dan pengaduan pagi')
                                ->schema([
                                    Select::make('pagi_pelayanan_id')
                                        ->label('Petugas Pelayanan')
                                        ->options($allOptions)
                                        ->searchable()
                                        ->placeholder('-- Pilih Petugas Pelayanan --')
                                        ->default($dailySlots['pagi_pelayanan_id'] ?? null),
                                    Select::make('pagi_pengaduan_id')
                                        ->label('Petugas Pengaduan / Disabilitas')
                                        ->options($allOptions)
                                        ->searchable()
                                        ->placeholder('-- Pilih Petugas Pengaduan --')
                                        ->default($dailySlots['pagi_pengaduan_id'] ?? null),
                                ]),
                            \Filament\Schemas\Components\Section::make('🌤️ Shift Siang (12.00 - 15.30)')
                                ->description('Piket pelayanan dan pengaduan siang')
                                ->schema([
                                    Select::make('siang_pelayanan_id')
                                        ->label('Petugas Pelayanan')
                                        ->options($allOptions)
                                        ->searchable()
                                        ->placeholder('-- Pilih Petugas Pelayanan --')
                                        ->default($dailySlots['siang_pelayanan_id'] ?? null),
                                    Select::make('siang_pengaduan_id')
                                        ->label('Petugas Pengaduan / Disabilitas')
                                        ->options($allOptions)
                                        ->searchable()
                                        ->placeholder('-- Pilih Petugas Pengaduan --')
                                        ->default($dailySlots['siang_pengaduan_id'] ?? null),
                                ]),
                        ]),
                    Toggle::make('send_whatsapp')
                        ->label('Kirim Notifikasi WhatsApp ke Petugas Baru')
                        ->default(true),
                ];
            })
            ->action(function (array $data, array $arguments) {
                $tanggal = $data['tanggal'] ?? $arguments['tanggal'];
                $service = app(JadwalPiketService::class);

                try {
                    $service->saveDailySlots($tanggal, $data, (bool) ($data['send_whatsapp'] ?? false));
                    Notification::make()
                        ->title('Jadwal Berhasil Disimpan!')
                        ->body('Jadwal piket tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y') . ' telah diperbarui.')
                        ->success()
                        ->send();
                } catch (\InvalidArgumentException $e) {
                    Notification::make()
                        ->title('Gagal Menyimpan!')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    public function generateRotationAction(): Action
    {
        return Action::make('generateRotation')
            ->label('Rotasi Otomatis (Generate)')
            ->icon('heroicon-o-sparkles')
            ->color('primary')
            ->modalHeading('Generate Rotasi Piket Bulanan')
            ->modalDescription('Sistem akan menyusun giliran piket (4 orang/hari) secara adil untuk hari kerja (Senin - Jumat) dalam satu bulan.')
            ->modalSubmitActionLabel('Generate Jadwal')
            ->form([
                \Filament\Schemas\Components\Grid::make(2)
                    ->schema([
                        Select::make('bulan')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ])
                            ->default(fn () => $this->currentMonth)
                            ->required(),
                        Select::make('tahun')
                            ->label('Tahun')
                            ->options(collect(range(2025, 2035))->mapWithKeys(fn ($y) => [$y => (string) $y]))
                            ->default(fn () => $this->currentYear)
                            ->required(),
                    ]),
                CheckboxList::make('officer_ids')
                    ->label('Daftar Petugas yang Diikutsertakan')
                    ->options(PegawaiPst::orderBy('nama_pegawai')->pluck('nama_pegawai', 'id'))
                    ->default(PegawaiPst::pluck('id')->toArray())
                    ->columns(2)
                    ->searchable()
                    ->required(),
                Toggle::make('overwrite_existing')
                    ->label('Timpa Jadwal yang Sudah Ada pada Bulan Ini?')
                    ->helperText('Jika tidak dicentang, tanggal yang sudah terisi lengkap tidak akan diubah.')
                    ->default(false),
            ])
            ->action(function (array $data) {
                $service = app(JadwalPiketService::class);
                try {
                    $days = $service->generateMonthlyRotation(
                        (int) $data['tahun'],
                        (int) $data['bulan'],
                        (array) $data['officer_ids'],
                        (bool) ($data['overwrite_existing'] ?? false)
                    );
                    Notification::make()
                        ->title('Rotasi Otomatis Berhasil!')
                        ->body("Jadwal piket untuk {$days} hari kerja telah berhasil dibuat.")
                        ->success()
                        ->send();
                } catch (\Throwable $e) {
                    Notification::make()
                        ->title('Gagal!')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
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

        while ($currentDate <= $endOfCalendar) {
            $dateString = $currentDate->format('Y-m-d');
            $hariJadwal = $jadwals->get($dateString, collect());

            $pagiPelayanan = $hariJadwal->first(fn($j) => $j->shift === 'Pagi' && $j->jenis_piket === 'Pelayanan');
            $pagiPengaduan = $hariJadwal->first(fn($j) => $j->shift === 'Pagi' && $j->jenis_piket === 'Pengaduan/Disabilitas');
            $siangPelayanan = $hariJadwal->first(fn($j) => $j->shift === 'Siang' && $j->jenis_piket === 'Pelayanan');
            $siangPengaduan = $hariJadwal->first(fn($j) => $j->shift === 'Siang' && $j->jenis_piket === 'Pengaduan/Disabilitas');

            $days[] = [
                'date' => $currentDate->copy(),
                'dateString' => $dateString,
                'isCurrentMonth' => $currentDate->month === $this->currentMonth,
                'isWeekend' => $currentDate->isWeekend(),
                'slots' => [
                    'pagi_pelayanan' => $pagiPelayanan,
                    'pagi_pengaduan' => $pagiPengaduan,
                    'siang_pelayanan' => $siangPelayanan,
                    'siang_pengaduan' => $siangPengaduan,
                ],
                'filledCount' => $hariJadwal->count(),
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
