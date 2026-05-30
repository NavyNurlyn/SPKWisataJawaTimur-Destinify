<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    KriteriaController,
    AlternatifController,
    PembobotanController,
    PerhitunganController,
    RekomendasiController
};

// =======================
// DASHBOARD
// =======================
Route::get('/', function () {
    $alternatifCount = \App\Models\Alternatif::count();
    $kriteriaCount = \App\Models\Kriteria::count();
    $ahpKonsisten = session('ahp_konsisten', false);

    return view('dashboard.index', compact('alternatifCount', 'kriteriaCount', 'ahpKonsisten'));
})->name('dashboard');

// =======================
// CRUD UTAMA
// =======================
Route::resource('kriteria', KriteriaController::class);
Route::resource('alternatif', AlternatifController::class);

// =======================
// PEMBOBOTAN KRITERIA (AHP)
// =======================
Route::get('/pembobotan', [PembobotanController::class, 'index'])->name('pembobotan.index');
Route::post('/pembobotan/update', [PembobotanController::class, 'updateNilai'])->name('pembobotan.update');
Route::post('/pembobotan/hitung', [PembobotanController::class, 'hitung'])->name('pembobotan.hitung');

// =======================
// PERHITUNGAN (Preview AHP & TOPSIS)
// =======================
Route::get('/perhitungan', [PerhitunganController::class, 'index'])->name('perhitungan.index');

// =======================
// REKOMENDASI (User Interface)
// =======================
Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
Route::post('/rekomendasi/hitung', [RekomendasiController::class, 'hitung'])->name('rekomendasi.hitung');
