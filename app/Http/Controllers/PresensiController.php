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
    public function store(Request $request)
    {
        $pegawaiId = $request->pegawai_id;
        $today = Carbon::today()->toDateString();

        $presensi = Presensi::where('pegawai_id', $pegawaiId)
            ->where('tanggal', $today)
            ->first();

        if (!$presensi) {
            // jika belum ada, isi jam_masuk
            Presensi::create([
                'pegawai_id' => $pegawaiId,
                'tanggal'    => $today,
                'jam_masuk'  => Carbon::now()->format('H:i:s'),
                'status'     => 'Hadir',
            ]);
            return redirect()->route('presensi.index')->with('success', 'Absensi masuk berhasil.');
        }

        if ($presensi && !$presensi->jam_selesai) {
            // jika sudah ada jam_masuk tapi belum selesai
            $presensi->update([
                'jam_selesai' => Carbon::now()->format('H:i:s'),
            ]);
            return redirect()->route('presensi.index')->with('success', 'Absensi selesai berhasil.');
        }

        return redirect()->route('presensi.index')->with('error', 'Anda sudah lengkap presensi hari ini.');
    }
    
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
