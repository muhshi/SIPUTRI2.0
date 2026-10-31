<?php

namespace App\Http\Controllers;

use App\Models\PegawaiPst;
use App\Models\Presensi;
use Illuminate\Http\Request;

class PrintPresensiController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Presensi::with('pegawai');

        $pegawaiId = is_array($request->pegawai_id) ? ($request->pegawai_id['value'] ?? null) : $request->pegawai_id;
        $tahun = is_array($request->tahun) ? ($request->tahun['value'] ?? null) : $request->tahun;
        $bulan = is_array($request->bulan) ? ($request->bulan['value'] ?? null) : $request->bulan;

        $selectedPegawai = null;
        if ($pegawaiId) {
            $query->where('pegawai_id', $pegawaiId);
            $selectedPegawai = PegawaiPst::find($pegawaiId);
        }

        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        return view('print.presensi', [
            'data' => $data,
            'selectedPegawai' => $selectedPegawai,
            'tahun' => (string) $tahun,
            'bulan' => (string) $bulan,
        ]);
    }
}
