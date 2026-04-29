<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\Auth\SsoController;

Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
Route::post('/presensi/form', [PresensiController::class, 'form'])->name('presensi.form');
Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');

Route::get('/form-pengunjung', [PengunjungController::class, 'index'])->name('pengunjung.form');
Route::post('/form-pengunjung', [PengunjungController::class, 'store'])->name('pengunjung.submit');

Route::post('/ambil-antrian', [QueueController::class, 'ambil'])->name('queue.ambil');
Route::get('/struk-antrian/{id}', [QueueController::class, 'cetakStruk'])->name('queue.struk');

Route::get('/lihat-antrian', [QueueController::class, 'lihat'])->name('queue.lihat');
Route::middleware(['auth'])->group(function () {
    Route::post('/queue/panggil/{id}', [QueueController::class, 'panggil'])->name('queue.panggil');
    Route::post('/queue/selesai/{id}', [QueueController::class, 'selesai'])->name('queue.selesai');
    Route::post('/queue/batal/{id}', [QueueController::class, 'batal'])->name('queue.batal');
});

Route::get('/evaluasi', [EvaluasiController::class, 'index'])->name('evaluasi.index');
Route::post('/evaluasi/store', [EvaluasiController::class, 'store'])->name('evaluasi.store');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/print/kunjungan', \App\Http\Controllers\PrintKunjunganController::class)->name('print.kunjungan');//->middleware('auth'); // Uncomment middleware if auth is required and working

Route::get('/auth/sipetra/redirect', [SsoController::class, 'redirect'])->name('sipetra.login');
Route::get('/auth/sipetra/callback', [SsoController::class, 'callback']);

