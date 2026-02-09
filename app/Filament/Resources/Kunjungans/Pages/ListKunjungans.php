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
            \Filament\Actions\Action::make('export_csv')
                ->label('Export CSV (Excel)')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $columns = [
                            'Nama',
                            'Instansi',
                            'Tanggal',
                            'Jenis Kelamin',
                            'Pendidikan',
                            'Pekerjaan',
                            'Email',
                            'Pemanfaatan',
                            'Layanan',
                            'Umur',
                            'Alamat'
                        ];

                        $file = fopen('php://output', 'w');
                        fputcsv($file, $columns);

                        // Use query structure similar to table to respect filters if possible,
                        // but for now we export all data as requested or base query.
                        // Ideally we'd use the table's query, but that requires more complex context injection.
                        // We will export ALL data for now as per "Export mencakup seluruh data".
                        $query = \App\Models\Kunjungan::query();

                        // Optimize chunking for performance
                        $query->chunk(100, function ($rows) use ($file) {
                            foreach ($rows as $row) {
                                fputcsv($file, [
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
                                ]);
                            }
                        });

                        fclose($file);
                    }, 'Data-Kunjungan-' . date('Y-m-d') . '.csv');
                }),

            \Filament\Actions\Action::make('print_pdf')
                ->label('Print / Save PDF')
                ->icon('heroicon-o-printer')
                ->url(fn(): string => route('print.kunjungan'))
                ->openUrlInNewTab(),

            CreateAction::make(),
        ];
    }
}
