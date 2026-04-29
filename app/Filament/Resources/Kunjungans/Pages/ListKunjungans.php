<?php

namespace App\Filament\Resources\Kunjungans\Pages;

use App\Filament\Resources\Kunjungans\KunjunganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKunjungans extends ListRecords
{
    protected static string $resource = KunjunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_xlsx')
                ->label('Export Excel (.xlsx)')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $writer = new \OpenSpout\Writer\XLSX\Writer();
                        $writer->openToFile('php://output');

                        // Header Row
                        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
                            'Nama', 'Instansi', 'Tanggal', 'Jenis Kelamin', 'Pendidikan', 
                            'Pekerjaan', 'Email', 'Pemanfaatan', 'Layanan', 'Umur', 'Alamat'
                        ]));

                        // Use the filtered query from the table to respect filters
                        $query = $this->getFilteredTableQuery();

                        // chunk for performance
                        $query->chunk(100, function ($rows) use ($writer) {
                            foreach ($rows as $row) {
                                $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues([
                                    $row->nama,
                                    $row->instansi,
                                    $row->tanggal,
                                    $row->jenis_kelamin,
                                    $row->pendidikan,
                                    $row->pekerjaan,
                                    $row->email,
                                    $row->pemanfaatan,
                                    $row->layanan,
                                    $row->umur,
                                    $row->alamat,
                                ]));
                            }
                        });

                        $writer->close();
                    }, 'Data-Kunjungan-' . date('Y-m-d') . '.xlsx');
                }),

            \Filament\Actions\Action::make('print_pdf')
                ->label('Print / Save PDF')
                ->icon('heroicon-o-printer')
                ->url(fn(): string => route('print.kunjungan', [
                    'tahun' => $this->tableFilters['tahun']['tahun'] ?? null,
                    'triwulan' => $this->tableFilters['triwulan']['triwulan'] ?? null,
                ]))
                ->openUrlInNewTab(),

            CreateAction::make(),
        ];
    }
}
