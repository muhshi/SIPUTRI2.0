<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluasi;
use App\Models\PegawaiPst;



class EvaluasiController extends Controller
{
    public function index()
    {
        $nama = session('nama_pengunjung', 'Pengunjung');
        $pengunjungId = session('pengunjung_id');

        if (!$pengunjungId) {
            return redirect()
                ->route('pengunjung.form')
                ->with('error', 'Silahkan isi form kunjungan terlebih dahulu.');
        }

        $sudahEvaluasi = Evaluasi::where('pengunjung_id', $pengunjungId)->exists();

        $pegawai = PegawaiPst::whereHas('presensis', function ($query) {
            $query->whereDate('tanggal', now());
        })->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama_pegawai,
                'gambar' => $p->foto_pegawai ?? 'default.jpg',
                'jabatan' => $p->jabatan,
            ];
        });

        return view('evaluasi.evaluasi', compact('nama', 'pegawai', 'sudahEvaluasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawai_psts,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $pengunjungId = session('pengunjung_id');

        if (!$pengunjungId) {
            return redirect()
                ->route('pengunjung.form')
                ->with('error', 'Session tidak ditemukan. Silahkan isi form terlebih dahulu.');
        }

        // Cegah double submit
        if (Evaluasi::where('pengunjung_id', $pengunjungId)->exists()) {
            return redirect()
                ->route('evaluasi.index')
                ->with('error', 'Anda sudah memberikan penilaian.');
        }

        Evaluasi::create([
            'pegawai_id' => $request->pegawai_id,
            'rating' => $request->rating,
            'pengunjung_id' => $pengunjungId,
        ]);

        return redirect()
            ->route('evaluasi.index')
            ->with('success', 'Terima kasih atas penilaian Anda!');
    }
}