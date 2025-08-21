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

        $lastQueue = Queue::whereDate('tanggal', $today)->latest()->first();
        $nomorBaru = $lastQueue ? $lastQueue->nomor + 1 : 1;

        $antrian = Queue::create([
            'layanan' => $request->layanan,
            'nomor'   => $nomorBaru,
            'tanggal' => $today,
        ]);

        return redirect()->route('queue.struk', $antrian->id); // <-- titik koma yang benar
    }

    public function lihat()
    {
        $today = Carbon::today();

        $queues = Queue::whereDate('tanggal', $today)->orderBy('created_at')->get();

        $current = $queues->last();

        $total = $queues->count();

        return view('queue.lihat', compact('queues', 'current', 'total'));
    }

    public function cetakStruk($id)
    {
        $antrian = Queue::findOrFail($id);

        return view('queue.struk', compact('antrian'));
    }
}
