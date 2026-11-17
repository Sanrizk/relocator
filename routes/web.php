<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SpkController;
use Illuminate\Support\Facades\Route;

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (Admin & Pimpinan)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Modul SPK & Hasil (Dapat dilihat oleh Admin & Pimpinan)
    Route::get('/spk/hasil', [SpkController::class, 'index'])->name('spk.index');
    Route::get('/spk/cetak', [SpkController::class, 'cetakPdf'])->name('spk.cetak');

    // Modul Khusus Admin (CRUD Kriteria, Alternatif, Matriks Penilaian)
    Route::middleware('role:admin')->group(function () {
        // Kriteria
        Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria.index');
        Route::post('/kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
        Route::put('/kriteria/{kriteria}', [KriteriaController::class, 'update'])->name('kriteria.update');
        Route::delete('/kriteria/{kriteria}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');
        Route::post('/kriteria/hitung-roc', [KriteriaController::class, 'hitungRoc'])->name('kriteria.hitung-roc');

        // Alternatif
        Route::get('/alternatif', [AlternatifController::class, 'index'])->name('alternatif.index');
        Route::post('/alternatif', [AlternatifController::class, 'store'])->name('alternatif.store');
        Route::put('/alternatif/{alternatif}', [AlternatifController::class, 'update'])->name('alternatif.update');
        Route::delete('/alternatif/{alternatif}', [AlternatifController::class, 'destroy'])->name('alternatif.destroy');

        // Matriks Penilaian
        Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
        Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
    });
});
