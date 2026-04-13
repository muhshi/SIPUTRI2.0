<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class PrintKunjunganController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Kunjungan::query();

        // Extract values if input is nested array (Filament format)
        $tahun = is_array($request->tahun) ? ($request->tahun['tahun'] ?? null) : $request->tahun;
        $triwulan = is_array($request->triwulan) ? ($request->triwulan['triwulan'] ?? null) : $request->triwulan;

        // Filter Tahun
        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        // Filter Triwulan
        if ($triwulan) {
            $quarter = (int) $triwulan;
            $ranges = [
                1 => [1, 3],
                2 => [4, 6],
                3 => [7, 9],
                4 => [10, 12],
            ];

            if (isset($ranges[$quarter])) {
                [$start, $end] = $ranges[$quarter];
                $query->whereMonth('tanggal', '>=', $start)
                      ->whereMonth('tanggal', '<=', $end);
            }
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        return view('print.kunjungan', [
            'data' => $data,
            'tahun' => (string) $tahun,
            'triwulan' => (string) $triwulan,
        ]);
    }
}
