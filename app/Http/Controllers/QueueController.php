<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use Carbon\Carbon;

class QueueController extends Controller
{
    public function ambil(Request $request)
    {
        $today = Carbon::today();

        // Cari nomor terakhir untuk jenis (kategori) yang sama pada hari ini
        $lastQueue = Queue::whereDate('tanggal', $today)
            ->where('jenis', $request->jenis)
            ->latest('id')
            ->first();

        $nomorBaru = $lastQueue ? (int) $lastQueue->nomor + 1 : 1;

        $antrian = Queue::create([
            'layanan' => $request->layanan, // Ini adalah sub-layanan (misal: Permintaan Data)
            'jenis' => $request->jenis,     // Ini adalah kategori (Layanan, Pengaduan, Disabilitas)
            'nomor' => $nomorBaru,
            'tanggal' => $today,
        ]);

        return redirect()->route('queue.struk', $antrian->id);
    }

    public function lihat()
    {
        $today = Carbon::today();

        $layanan = Queue::whereDate('tanggal', $today)
            ->where('jenis', 'Layanan')
            ->orderBy('nomor', 'asc')
            ->get();

        $pengaduan = Queue::whereDate('tanggal', $today)
            ->where('jenis', 'Pengaduan')
            ->orderBy('nomor', 'asc')
            ->get();

        $disabilitas = Queue::whereDate('tanggal', $today)
            ->where('jenis', 'Disabilitas')
            ->orderBy('nomor', 'asc')
            ->get();

        $current = Queue::whereDate('tanggal', $today)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('queue.lihat', compact('layanan', 'pengaduan', 'disabilitas', 'current'));
    }

    public function cetakStruk($id)
    {
        $antrian = Queue::findOrFail($id);

        return view('queue.struk', compact('antrian'));
    }
}
