<?php

namespace App\Filament\Resources\JadwalPikets\Pages;

use App\Filament\Resources\JadwalPikets\JadwalPiketResource;
use App\Services\JadwalPiketService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditJadwalPiket extends EditRecord
{
    protected static string $resource = JadwalPiketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $service = app(JadwalPiketService::class);
        $record = $this->getRecord();
        $date = $record->tanggal ?? $data['tanggal'];
        $slots = $service->getDailySlots($date);

        if ($record->tanggal) {
            $data['tanggal'] = $record->tanggal->format('Y-m-d');
        }

        return array_merge($data, $slots);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $service = app(JadwalPiketService::class);
        $saved = $service->saveDailySlots(
            $data['tanggal'] ?? $record->tanggal,
            $data,
            (bool) ($data['send_whatsapp'] ?? false)
        );

        $first = collect($saved)->first(fn ($item) => $item !== null);

        return $first ?? $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
