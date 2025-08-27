<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluasi;

class EvaluasiController extends Controller
{
    public function index()
    {
        $nama = session('nama_pengunjung', 'Pengunjung');
        $pengunjungId = session('pengunjung_id');

        // Jika tidak ada session pengunjung, redirect ke form
        if (!$pengunjungId) {
            return redirect()->route('pengunjung.form')->with('error', 'Silahkan isi form kunjungan terlebih dahulu.');
        }

        // Cek apakah sudah memberi evaluasi
        $sudahEvaluasi = Evaluasi::where('pengunjung_id', $pengunjungId)->exists();

        $pegawai = [
            [
                'id' => 1,
                'nama' => 'Henri Wagiyanto S.Pt., M.Ec.Dev., M.A.',
                'gambar' => '11.jpg',
                'jabatan' => 'Pengarah',
            ],
            [
                'id' => 2,
                'nama' => 'M. Masykuri Zaen, S.ST.',
                'gambar' => '22.jpg',
                'jabatan' => 'Koordinator',
            ],
            [
                'id' => 3,
                'nama' => 'Paramitha Hanifia S.ST.',
                'gambar' => 'citra.jpg',
                'jabatan' => 'Anggota Petugas',
            ],
            [
                'id' => 4,
                'nama' => 'M. Abdul Muhshi S.ST.',
                'gambar' => 'doni.jpg',
                'jabatan' => 'Anggota Petugas',
            ],
            [
                'id' => 5,
                'nama' => 'Wiwi Wilujeng, K.SE., M.M.',
                'gambar' => 'andi.jpg',
                'jabatan' => 'Anggota Petugas',
            ],
            [
                'id' => 6,
                'nama' => 'Nur Kurniawati, S.ST.',
                'gambar' => 'budi.jpg',
                'jabatan' => 'Anggota Petugas',
            ],
            [
                'id' => 7,
                'nama' => 'Muhammad Guntur Ilham, S.Tr.Stat.',
                'gambar' => 'citra.jpg',
                'jabatan' => 'Anggota Petugas',
            ],
            [
                'id' => 8,
                'nama' => 'Nunung Susanti, A.Md.',
                'gambar' => 'doni.jpg',
                'jabatan' => 'Anggota Petugas',
            ],
            [
                'id' => 9,
                'nama' => 'Dyah Makutaning Dewi, S.Tr.Stat.',
                'gambar' => 'andi.jpg',
                'jabatan' => 'Anggota Petugas',
            ],
            [
                'id' => 10,
                'nama' => 'Musyafaah, A.Md.',
                'gambar' => 'budi.jpg',
                'jabatan' => 'Anggota Petugas',
            ],
            [
                'id' => 11,
                'nama' => 'Aris Sutikno, S.E.',
                'gambar' => 'citra.jpg',
                'jabatan' => 'Anggota Petugas',
            ],
            [
                'id' => 12,
                'nama' => 'Yudia Pratidina Hasibuan, S.ST.',
                'gambar' => 'doni.jpg',
                'jabatan' => 'Anggota Petugas',
            ],
        ];

        return view('evaluasi.evaluasi', compact('nama', 'pegawai', 'sudahEvaluasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $pengunjungId = session('pengunjung_id');

        if (!$pengunjungId) {
            return redirect()->route('pengunjung.form')->with('error', 'Session tidak ditemukan. Silahkan isi form terlebih dahulu.');
        }

        // Double-check apakah sudah ada evaluasi untuk pengunjung ini
        $sudah = Evaluasi::where('pengunjung_id', $pengunjungId)->exists();

        if ($sudah) {
            return back()->with('error', 'Anda sudah memberikan penilaian sebelumnya.');
        }

        // Simpan rating
        Evaluasi::create([
            'pegawai_id' => $request->pegawai_id,
            'rating' => $request->rating,
            'pengunjung_id' => session('pengunjung_id'),

        ]);

        return redirect()->route('evaluasi.index')->with('success', 'Terima kasih atas penilaian Anda!');
    }
}
