<?php

namespace App\Filament\Resources\Presensis\Pages;

use App\Filament\Resources\Presensis\PresensiResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListPresensis extends ListRecords
{
    protected static string $resource = PresensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_xlsx')
                ->label('Export Excel (.xlsx)')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $writer = new \OpenSpout\Writer\XLSX\Writer();
                        $writer->openToFile('php://output');

                        // Header Row
                        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
                            'No', 'Nama Pegawai', 'NIP', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status'
                        ]));

                        // Use filtered table query to respect filters
                        $query = $this->getFilteredTableQuery();

                        $no = 1;
                        $query->chunk(100, function ($rows) use ($writer, &$no) {
                            foreach ($rows as $row) {
                                $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
                                    $no++,
                                    $row->pegawai?->nama_pegawai ?? '-',
                                    $row->pegawai?->nip ?? '-',
                                    $row->tanggal ?? '',
                                    $row->jam_masuk ?? '-',
                                    $row->jam_selesai ?? '-',
                                    $row->status ?? 'Hadir',
                                ]));
                            }
                        });

                        $writer->close();
                    }, 'Rekap-Presensi-' . date('Y-m-d') . '.xlsx');
                }),

            Action::make('print_pdf')
                ->label('Print / Save PDF')
                ->icon('heroicon-o-printer')
                ->url(fn(): string => route('print.presensi', [
                    'pegawai_id' => $this->tableFilters['pegawai']['value'] ?? null,
                ]))
                ->openUrlInNewTab(),

            Action::make('buat_presensi')
                ->label('Buat presensi')
                ->url('/presensi?mode=manual')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }
}
