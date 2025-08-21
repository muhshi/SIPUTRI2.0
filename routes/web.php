<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\PresensiController;

Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
Route::post('/presensi/form', [PresensiController::class, 'form'])->name('presensi.form');
Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');

Route::get('/form-pengunjung', [PengunjungController::class, 'index'])->name('pengunjung.form');
Route::post('/form-pengunjung', [PengunjungController::class, 'store'])->name('pengunjung.submit');

Route::post('/ambil-antrian', [QueueController::class, 'ambil'])->name('queue.ambil');
Route::get('/struk-antrian/{id}', [QueueController::class, 'cetakStruk'])->name('queue.struk');

Route::get('/lihat-antrian', [QueueController::class, 'lihat'])->name('queue.lihat');

Route::get('/evaluasi', [EvaluasiController::class, 'index'])->name('evaluasi.index');
Route::post('/evaluasi/store', [EvaluasiController::class, 'store'])->name('evaluasi.store');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
