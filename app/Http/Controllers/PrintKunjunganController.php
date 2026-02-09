<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class PrintKunjunganController extends Controller
{
    public function __invoke()
    {
        $data = Kunjungan::all();

        return view('print.kunjungan', compact('data'));
    }
}
