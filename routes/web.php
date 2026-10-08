<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisAsetController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriAsetController;
use App\Http\Controllers\SubKategoriAsetController;
<<<<<<< HEAD

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Jenis Aset
Route::get('/jenis-aset', [JenisAsetController::class, 'index'])->name('jenis-aset.index');
Route::get('/jenis-aset/create', [JenisAsetController::class, 'create'])->name('jenis-aset.create');
Route::post('/jenis-aset', [JenisAsetController::class, 'store'])->name('jenis-aset.store');
Route::get('/jenis-aset/{id}', [JenisAsetController::class, 'show'])->name('jenis-aset.show');
Route::get('/jenis-aset/{id}/edit', [JenisAsetController::class, 'edit'])->name('jenis-aset.edit');
Route::put('/jenis-aset/{id}', [JenisAsetController::class, 'update'])->name('jenis-aset.update');
Route::patch('/jenis-aset/{id}/status', [JenisAsetController::class, 'ubahStatus'])->name('jenis-aset.status');

// Aset
Route::get('/aset', [AsetController::class, 'index'])->name('aset.index');
Route::get('/aset/{id}', [AsetController::class, 'show'])->name('aset.show');
Route::get('/aset/{id}/edit', [AsetController::class, 'edit'])->name('aset.edit');
Route::put('/aset/{id}', [AsetController::class, 'update'])->name('aset.update');
=======
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisAsetController;
use App\Http\Controllers\LokasiAsetController;
>>>>>>> 6ed3bc9dd979944db5e604da98e9956524975cee

// =====================================================
// LOGIN
// =====================================================

Route::get('/login', [UserController::class, 'login'])
    ->name('login');

Route::post('/login', [UserController::class, 'authenticate'])
    ->name('login.process');

Route::post('/logout', [UserController::class, 'logout'])
    ->name('logout');


// =====================================================
// DASHBOARD
// =====================================================

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');


// =====================================================
// KATEGORI ASET
// =====================================================

Route::get('/kategori', [KategoriAsetController::class, 'index'])
    ->name('kategori.index');

Route::get('/kategori/create', [KategoriAsetController::class, 'create'])
    ->name('kategori.create');

Route::post('/kategori', [KategoriAsetController::class, 'store'])
    ->name('kategori.store');

// Halaman update kategori
Route::get('/kategori/{id}/update', [KategoriAsetController::class, 'edit'])
    ->name('kategori.update.form');

// Proses update kategori
Route::put('/kategori/{id}', [KategoriAsetController::class, 'update'])
    ->name('kategori.update');


// Hapus kategori
Route::delete('/kategori/{id}', [KategoriAsetController::class, 'destroy'])
    ->name('kategori.destroy');

Route::get('/kategori/{kategori}', [KategoriAsetController::class, 'show'])
    ->name('kategori.show');


// =====================================================
// SUB KATEGORI ASET
// =====================================================

/// SUB KATEGORI
Route::get('/kategori/{id_kategori}/sub-kategori', [SubKategoriAsetController::class, 'index'])
    ->name('kategori.sub-kategori.index');

Route::get('/kategori/{id_kategori}/sub-kategori/create', [SubKategoriAsetController::class, 'create'])
    ->name('kategori.sub-kategori.create');

Route::post('/kategori/{id_kategori}/sub-kategori', [SubKategoriAsetController::class, 'store'])
    ->name('kategori.sub-kategori.store');

Route::get('/kategori/{id_kategori}/sub-kategori/{id}/update', [SubKategoriAsetController::class, 'edit'])
    ->name('kategori.sub-kategori.edit');

Route::put('/kategori/{id_kategori}/sub-kategori/{id}', [SubKategoriAsetController::class, 'update'])
    ->name('kategori.sub-kategori.update');

Route::delete('/kategori/{id_kategori}/sub-kategori/{id}', [SubKategoriAsetController::class, 'destroy'])
    ->name('kategori.sub-kategori.destroy');

// =====================================================
// JENIS ASET
// =====================================================

Route::get('/jenis-aset', [JenisAsetController::class, 'index'])
    ->name('jenis-aset.index');

Route::get('/jenis-aset/tambah', [JenisAsetController::class, 'create'])
    ->name('jenis-aset.create');

Route::post('/jenis-aset', [JenisAsetController::class, 'store'])
    ->name('jenis-aset.store');

Route::get('/jenis-aset/{id}/edit', [JenisAsetController::class, 'edit'])
    ->name('jenis-aset.edit');

Route::put('/jenis-aset/{id}', [JenisAsetController::class, 'update'])
    ->name('jenis-aset.update');

Route::patch('/jenis-aset/{id}/status', [JenisAsetController::class, 'ubahStatus'])
    ->name('jenis-aset.status');

Route::get('/jenis-aset/{id}', [JenisAsetController::class, 'show'])
    ->name('jenis-aset.show');

// LOKASI ASET
Route::get('/lokasi', [LokasiAsetController::class, 'index'])
    ->name('lokasi.index');

Route::get('/lokasi/create', [LokasiAsetController::class, 'create'])
    ->name('lokasi.create');

Route::post('/lokasi', [LokasiAsetController::class, 'store'])
    ->name('lokasi.store');

// Edit hanya deskripsi
Route::get('/lokasi/{id}/edit', [LokasiAsetController::class, 'edit'])
    ->name('lokasi.edit');

Route::put('/lokasi/{id}', [LokasiAsetController::class, 'update'])
    ->name('lokasi.update');

// Detail lokasi
Route::get('/lokasi/{id}', [LokasiAsetController::class, 'show'])
    ->name('lokasi.show');
    