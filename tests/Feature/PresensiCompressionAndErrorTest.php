<?php

namespace Tests\Feature;

use App\Models\PegawaiPst;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PresensiCompressionAndErrorTest extends TestCase
{
    use RefreshDatabase;

    public function test_presensi_image_is_compressed_and_saved(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'is_admin' => true,
        ]);
        $pegawai = PegawaiPst::create([
            'user_id' => $user->id,
            'nama_pegawai' => 'Test Pegawai',
            'nip' => '199001012020011001',
        ]);

        // Create a 1200x900 test image in memory
        $img = imagecreatetruecolor(1200, 900);
        $white = imagecolorallocate($img, 255, 255, 255);
        imagefilledrectangle($img, 0, 0, 1200, 900, $white);
        ob_start();
        imagejpeg($img);
        $rawBytes = ob_get_clean();
        imagedestroy($img);

        $base64Image = 'data:image/jpeg;base64,' . base64_encode($rawBytes);

        $response = $this->actingAs($user)->postJson('/presensi', [
            'pegawai_id' => $pegawai->id,
            'image' => $base64Image,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $files = Storage::disk('public')->files('presensi');
        $this->assertNotEmpty($files);

        $savedContents = Storage::disk('public')->get($files[0]);
        $savedImg = imagecreatefromstring($savedContents);
        $this->assertNotFalse($savedImg);
        $this->assertLessThanOrEqual(800, imagesx($savedImg));
        imagedestroy($savedImg);
    }

    public function test_presensi_error_does_not_leak_file_or_line(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        // Send invalid data that triggers exception or handling
        $response = $this->actingAs($user)->postJson('/presensi', [
            'pegawai_id' => 'not-an-id',
            'image' => 'data:image/jpeg;base64,invalid-base64-string',
        ]);

        $json = $response->json();
        $this->assertArrayNotHasKey('file', $json);
        $this->assertArrayNotHasKey('line', $json);
    }
}
