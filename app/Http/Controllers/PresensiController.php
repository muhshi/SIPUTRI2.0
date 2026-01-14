<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Pegawai;
use Carbon\Carbon;

class PresensiController extends Controller
{
    // Halaman utama
    public function index()
    {
        $pegawais = Pegawai::orderBy('nama')->get();
        $presensis = Presensi::with('pegawai')
            ->whereDate('tanggal', today())
            ->get();

        return view('presensi.index', compact('pegawais', 'presensis'));
    }

    // Halaman form presensi untuk pegawai tertentu
    public function form(Request $request)
    {
        $pegawai = Pegawai::findOrFail($request->pegawai_id);

        // cek data presensi hari ini
        $presensi = Presensi::where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', today())
            ->first();

        return view('presensi.form', compact('pegawai', 'presensi'));
    }

    // Submit presensi (masuk / selesai)
    // Submit presensi (masuk / selesai) with Image
    public function store(Request $request)
    {
        $pegawaiId = $request->pegawai_id;
        $image = $request->image;
        $today = Carbon::today()->toDateString();

        if (!$image) {
            return response()->json(['status' => 'error', 'message' => 'Foto tidak ditemukan.']);
        }

        // Process Base64 Image
        $image_parts = explode(";base64,", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'presensi_' . $pegawaiId . '_' . time() . '.jpg';
        $filePath = 'presensi/' . $fileName;

        // Save Image to Storage
        \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);

        $presensi = Presensi::where('pegawai_id', $pegawaiId)
            ->where('tanggal', $today)
            ->first();

        if (!$presensi) {
            // Check-in (Masuk)
            Presensi::create([
                'pegawai_id' => $pegawaiId,
                'tanggal' => $today,
                'jam_masuk' => Carbon::now()->format('H:i:s'),
                'foto_masuk' => $filePath,
                'status' => 'Hadir',
            ]);
            return response()->json([
                'status' => 'success',
                'type' => 'masuk',
                'message' => 'Halo, Selamat Bekerja! Absensi Masuk Berhasil.'
            ]);
        }

        if ($presensi) {
            // Check-out / Update Check-out (Pulang)
            // Logic: Foto selanjutnya updates jam_selesai and foto_keluar

            // Delete old foto_keluar if exists (optional to save space)
            if ($presensi->foto_keluar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($presensi->foto_keluar);
            }

            $presensi->update([
                'jam_selesai' => Carbon::now()->format('H:i:s'),
                'foto_keluar' => $filePath
            ]);

            return response()->json([
                'status' => 'success',
                'type' => 'pulang',
                'message' => 'Hati-hati di jalan! Absensi Pulang Berhasil Diupdate.'
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan sistem.']);
    }

    // Relationship removed (incorrectly placed in Controller)
}
