<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\PegawaiPst;
use Carbon\Carbon;

class PresensiController extends Controller
{
    // Halaman utama
    public function index()
    {
        $pegawais = PegawaiPst::orderBy('nama_pegawai')->get();

        // Data presensi hari ini (untuk tabel / list jika ada)
        $presensis = Presensi::with('pegawai')
            ->whereDate('tanggal', today())
            ->get();

        // 🔥 TAMBAHAN: untuk cek status tombol Masuk / Pulang
        $todayPresensi = Presensi::whereDate('tanggal', today())
            ->get()
            ->keyBy('pegawai_id');

        return view('presensi.index', compact('pegawais', 'presensis', 'todayPresensi'));
    }


    // Halaman form presensi untuk pegawai tertentu
    public function form(Request $request)
    {
        $pegawai = PegawaiPst::findOrFail($request->pegawai_id);

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
        try {
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
                // ======================
                // PRESENSI MASUK
                // ======================
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

            if ($presensi && !$presensi->jam_selesai) {
                // ======================
                // PRESENSI PULANG
                // ======================

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
                    'message' => 'Hati-hati di jalan! Absensi Pulang Berhasil.'
                ]);
            }
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan sistem.']);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    // Relationship removed (incorrectly placed in Controller)
}
