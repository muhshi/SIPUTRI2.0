<?php

namespace App\Filament\Resources\PegawaiPsts\Pages;

use App\Filament\Resources\PegawaiPsts\PegawaiPstResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePegawaiPst extends CreateRecord
{
    protected static string $resource = PegawaiPstResource::class;

    public function mount(): void
    {
        parent::mount();

        $userId = request()->query('user_id');
        if ($userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                $this->form->fill([
                    'user_id' => $user->id,
                    'nama_pegawai' => $user->name,
                    'nip' => $user->nip,
                    'jabatan' => $user->jabatan,
                ]);
            }
        }
    }
}

