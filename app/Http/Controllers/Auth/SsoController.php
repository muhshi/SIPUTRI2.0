<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PegawaiPst;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class SsoController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('sipetra')->redirect();
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            $errorDescription = $request->get('error_description') ?? 'Login SSO dibatalkan oleh pengguna.';

            return redirect()->route('filament.admin.auth.login')->with('error', $errorDescription);
        }

        try {
            $ssoUser = Socialite::driver('sipetra')->user();
        } catch (\Exception $e) {
            Log::error('SIPETRA SSO Callback error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()->route('filament.admin.auth.login')->with(
                'error',
                'Gagal terhubung dengan server SIPETRA atau otentikasi tidak valid. Silakan coba kembali.'
            );
        }

        $rawData = $ssoUser->getRaw();

        // Cari user berdasarkan sipetra_id atau email
        $user = User::where('sipetra_id', $ssoUser->getId())->first()
             ?? User::where('email', $ssoUser->getEmail())->first();

        $data = [
            'sipetra_id'            => $ssoUser->getId(),
            'name'                  => $ssoUser->getName(),
            'email'                 => $ssoUser->getEmail(),
            'sipetra_token'         => $ssoUser->token,
            'sipetra_refresh_token' => $ssoUser->refreshToken ?? null,
            'nip'                   => $rawData['nip'] ?? null,
            'jabatan'               => $rawData['jabatan'] ?? null,
        ];

        if ($user) {
            $user->update($data);
        } else {
            $data['password'] = bcrypt(Str::random(24));
            $user = User::create($data);

            // Assign role default (Pegawai / pegawai)
            if (method_exists($user, 'assignRole')) {
                try {
                    $role = Role::where('name', 'Pegawai')
                        ->orWhere('name', 'pegawai')
                        ->first();
                    if ($role) {
                        $user->assignRole($role->name);
                    } else {
                        $user->assignRole('pegawai');
                    }
                } catch (\Throwable $e) {
                    Log::warning('Gagal assign role default SSO: ' . $e->getMessage());
                }
            }
        }

        // Auto-link ke PegawaiPst jika ada data dengan NIP yang sama
        if (! empty($user->nip)) {
            PegawaiPst::where('nip', $user->nip)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
        }

        Auth::login($user);

        Notification::make()
            ->title('Profil Berhasil Disinkronkan dari SIPETRA')
            ->success()
            ->send();

        return redirect()->intended('/admin');
    }
}
