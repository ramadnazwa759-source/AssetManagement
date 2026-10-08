<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisAsetController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriAsetController;
use App\Http\Controllers\SubKategoriAsetController;


// ROUTE FIKS
// =====================================================
// LOGIN
// =====================================================

Route::get('/', [UserController::class, 'login'])
    ->name('auth.login');

Route::post('/login', [UserController::class, 'authenticate'])
    ->name('auth.authenticate');

// =====================================================
// ASSET MANAGEMENT
// =====================================================
Route::middleware('asset.auth')->group(function () {

    Route::get(
        '/asset-management/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    Route::post(
        '/asset-management/logout',
        [UserController::class, 'logout']
    )->name('auth.logout');

    // Kategori Aset
    Route::get('/kategori', [KategoriAsetController::class, 'index'])
        ->name('kategori.index');

    Route::get('/kategori/tambah', [KategoriAsetController::class, 'create'])
        ->name('kategori.create');

    Route::post('/kategori', [KategoriAsetController::class, 'store'])
        ->name('kategori.store');

    Route::get('/kategori/{id}', [KategoriAsetController::class, 'show'])
        ->name('kategori.show');

    Route::get('/kategori/{id}/ubah', [KategoriAsetController::class, 'edit'])
        ->name('kategori.edit');

    Route::put('/kategori/{id}', [KategoriAsetController::class, 'update'])
        ->name('kategori.update');

    //Sub Kategori Aset
    Route::get(
        '/kategori/{id_kategori}/sub-kategori',
        [SubKategoriAsetController::class, 'index']
    )->name('kategori.sub-kategori.index');

    Route::get(
        '/kategori/{id_kategori}/sub-kategori/tambah',
        [SubKategoriAsetController::class, 'create']
    )->name('kategori.sub-kategori.create');

    Route::post(
        '/kategori/{id_kategori}/sub-kategori',
        [SubKategoriAsetController::class, 'store']
    )->name('kategori.sub-kategori.store');

    Route::get(
        '/kategori/{id_kategori}/sub-kategori/{id}/ubah',
        [SubKategoriAsetController::class, 'edit']
    )->name('kategori.sub-kategori.edit');

    Route::put(
        '/kategori/{id_kategori}/sub-kategori/{id}',
        [SubKategoriAsetController::class, 'update']
    )->name('kategori.sub-kategori.update');
});



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






// DASHBOARD
// =====================================================



// =====================================================
// KATEGORI ASET
// =====================================================




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
    