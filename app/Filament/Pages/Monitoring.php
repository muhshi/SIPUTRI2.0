<?php

namespace App\Filament\Pages;

use App\Models\Evaluasi;
use App\Models\Kunjungan;
use App\Models\PegawaiPst;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;

class Monitoring extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    protected string $view = 'filament.pages.monitoring';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen';

    protected static ?string $navigationLabel = 'Monitoring';

    protected static ?int $navigationSort = 11;

    protected static ?string $title = 'Monitoring Kinerja Pegawai';

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = [
            'month' => now()->format('m'),
            'year' => now()->format('Y'),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 4,
                    'lg' => 6,
                ])
                    ->schema([
                        Select::make('month')
                            ->columnSpan(1)
                            ->hiddenLabel()
                            ->placeholder('Pilih Bulan')
                            ->options([
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember',
                            ])
                            ->native(false)
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->prefixIconColor('info')
                            ->extraAttributes([
                                'class' => 'bg-blue-50/50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800/50',
                            ])
                            ->live()
                            ->required(),

                        Select::make('year')
                            ->columnSpan(1)
                            ->hiddenLabel()
                            ->placeholder('Pilih Tahun')
                            ->options(function () {
                                $years = range(2023, now()->year);
                                return array_combine($years, $years);
                            })
                            ->native(false)
                            ->prefixIcon('heroicon-m-calendar')
                            ->prefixIconColor('info')
                            ->extraAttributes([
                                'class' => 'bg-blue-50/50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800/50',
                            ])
                            ->live()
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        $month = $this->data['month'] ?? now()->format('m');
        $year = $this->data['year'] ?? now()->format('Y');

        $isSqlite = DB::getDriverName() === 'sqlite';
        $durationExpression = $isSqlite
            ? 'strftime("%s", waktu_selesai) - strftime("%s", waktu_mulai)'
            : 'timestampdiff(SECOND, waktu_mulai, waktu_selesai)';

        return $table
            ->query(
                PegawaiPst::query()
                    ->withCount([
                        'evaluasis as jml_layanan' => function (Builder $query) use ($month, $year) {
                            $query->whereMonth('tanggal_evaluasi', $month)
                                ->whereYear('tanggal_evaluasi', $year);
                        }
                    ])
                    ->withAvg([
                        'evaluasis as avg_rating' => function (Builder $query) use ($month, $year) {
                            $query->whereMonth('tanggal_evaluasi', $month)
                                ->whereYear('tanggal_evaluasi', $year);
                        }
                    ], 'rating')
                    ->addSelect([
                        'kehadiran_hari' => \App\Models\Presensi::selectRaw('count(distinct tanggal)')
                            ->whereColumn('pegawai_id', 'pegawai_psts.id')
                            ->whereMonth('tanggal', $month)
                            ->whereYear('tanggal', $year)
                            ->whereNotNull('jam_masuk')
                            ->whereNotNull('jam_selesai'),
                        'avg_durasi' => Kunjungan::selectRaw("avg({$durationExpression})")
                            ->whereColumn('pegawai_id', 'pegawai_psts.id')
                            ->whereMonth('tanggal', $month)
                            ->whereYear('tanggal', $year)
                            ->where('status', 'selesai')
                            ->whereNotNull('waktu_mulai')
                            ->whereNotNull('waktu_selesai'),
                    ])
            )
            ->columns([
                TextColumn::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('kehadiran_hari')
                    ->label('Kehadiran (Bulan Ini)')
                    ->suffix(' Hari')
                    ->placeholder('0 Hari')
                    ->sortable(),

                TextColumn::make('jml_layanan')
                    ->label('Jumlah Layanan')
                    ->suffix(' Layanan')
                    ->placeholder('0 Layanan')
                    ->sortable(),

                TextColumn::make('avg_durasi')
                    ->label('Durasi Rata-rata')
                    ->formatStateUsing(function ($state) {
                        if (!$state)
                            return '---';
                        $minutes = floor($state / 60);
                        $seconds = $state % 60;
                        return "{$minutes}m {$seconds}s";
                    })
                    ->placeholder('---')
                    ->sortable(),

                TextColumn::make('avg_rating')
                    ->label('Rating Rata-rata')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '0.0';
                        return number_format((float)$state, 1);
                    })
                    ->suffix(' / 5.0')
                    ->placeholder('0.0 / 5.0')
                    ->sortable(),
            ]);
    }
}
