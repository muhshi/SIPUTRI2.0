<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kunjungan;

class PengunjungController extends Controller
{
    public function index()
    {
        return view('pengunjung.form-pengunjung');
    }

    public function store(Request $request)
    {
        // Validasi semua field dari form
        $validated = $request->validate([
            'nama'           => 'required|string|max:255',
            'pendidikan'     => 'nullable|string|max:100',
            'tanggal'        => 'required|date',
            'pekerjaan'      => 'nullable|string',
            'jenis_kelamin'  => 'nullable|string',
            'instansi'       => 'nullable|string',
            'email'          => 'nullable|email',
            'pemanfaatan'    => 'nullable|string',
            'tahun_lahir'    => 'nullable|integer',
            'layanan'        => 'nullable|string',
            'umur'           => 'nullable|integer',
            'data_diinginkan'=> 'nullable|string',
            'alamat'         => 'nullable|string',
        ]);

        // Simpan ke database 
        $kunjungan = Kunjungan::create($validated);

        // Simpan nama ke session supaya bisa dipanggil di evaluasi
        session(['nama_pengunjung' => $validated['nama']]);

        // Redirect ke halaman utama
        return redirect()->route('welcome')
                         ->with('tamu', $kunjungan->nama)->with('success','Data pengunjung berhasil disimpan!');
    }
}
