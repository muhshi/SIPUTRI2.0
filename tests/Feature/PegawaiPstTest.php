<?php

namespace Tests\Feature;

use App\Models\PegawaiPst;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PegawaiPstTest extends TestCase
{
    use RefreshDatabase;
    public function test_pegawai_pst_auto_fills_and_syncs_with_user(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'muammal.adib@bps.go.id'],
            [
                'name' => 'Adib Sulton Muammal',
                'password' => bcrypt('password'),
                'nip' => '199501012020121001',
                'jabatan' => 'Pranata Komputer Pertama',
            ]
        );

        $pegawai = PegawaiPst::create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals('Adib Sulton Muammal', $pegawai->nama_pegawai);
        $this->assertEquals('199501012020121001', $pegawai->nip);
        $this->assertEquals('Pranata Komputer Pertama', $pegawai->jabatan);

        // Test relationships
        $this->assertEquals($user->id, $pegawai->user->id);
        $this->assertEquals($pegawai->id, $user->fresh()->pegawai->id);
    }

    public function test_pegawai_pst_links_user_by_matching_nip(): void
    {
        $user = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@bps.go.id',
            'password' => bcrypt('password'),
            'nip' => '198501012010121002',
            'jabatan' => 'Statistisi Ahli Muda',
        ]);

        $pegawai = PegawaiPst::create([
            'nama_pegawai' => 'Budi Santoso',
            'nip' => '198501012010121002',
        ]);

        $this->assertEquals($user->id, $pegawai->user_id);
        $this->assertEquals($pegawai->id, $user->fresh()->pegawai->id);
    }
}
