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
            ->orderBy('created_at', 'asc')
            ->get();

        $pengaduan = Queue::whereDate('tanggal', $today)
            ->where('jenis', 'Pengaduan')
            ->orderBy('created_at', 'asc')
            ->get();

        $disabilitas = Queue::whereDate('tanggal', $today)
            ->where('jenis', 'Disabilitas')
            ->orderBy('created_at', 'asc')
            ->get();

        // Ambil antrean yang sedang dipanggil (aktif)
        $myAntrian = Queue::whereDate('tanggal', $today)
            ->where('status', 'calling')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('queue.lihat', compact('layanan', 'pengaduan', 'disabilitas', 'myAntrian'));
    }

    public function panggil($id)
    {
        // Reset status 'calling' yang lain agar hanya satu yang aktif di display (opsional, tergantung kebutuhan multi-loket)
        // Queue::whereDate('tanggal', Carbon::today())->where('status', 'calling')->update(['status' => 'waiting']);

        $antrian = Queue::findOrFail($id);
        $antrian->update(['status' => 'calling']);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    public function selesai($id)
    {
        $antrian = Queue::findOrFail($id);
        $antrian->update(['status' => 'finished']);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    public function batal($id)
    {
        $antrian = Queue::findOrFail($id);
        $antrian->update(['status' => 'cancelled']);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    public function cetakStruk($id)
    {
        $antrian = Queue::findOrFail($id);

        return view('queue.struk', compact('antrian'));
    }
}
