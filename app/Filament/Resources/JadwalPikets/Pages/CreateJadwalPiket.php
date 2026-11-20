<?php

namespace App\Filament\Resources\JadwalPikets\Pages;

use App\Filament\Resources\JadwalPikets\JadwalPiketResource;
use App\Models\JadwalPiket;
use App\Services\JadwalPiketService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJadwalPiket extends CreateRecord
{
    protected static string $resource = JadwalPiketResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $service = app(JadwalPiketService::class);
        $saved = $service->saveDailySlots(
            $data['tanggal'],
            $data,
            (bool) ($data['send_whatsapp'] ?? false)
        );

        $first = collect($saved)->first(fn ($item) => $item !== null);

        if (! $first) {
            return JadwalPiket::whereDate('tanggal', $data['tanggal'])->first()
                ?? new JadwalPiket(['tanggal' => $data['tanggal']]);
        }

        return $first;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
