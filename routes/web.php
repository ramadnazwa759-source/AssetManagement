<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriAsetController;
use App\Http\Controllers\SubKategoriAsetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisAsetController;


// =====================================================
// LOGIN
// =====================================================

// Menampilkan halaman login
Route::get('/', [UserController::class, 'login'])
    ->name('login');

// Memproses login
Route::post('/login', [UserController::class, 'authenticate'])
    ->name('login.process');

// Logout
Route::post('/logout', [UserController::class, 'logout'])
    ->name('logout');


// =====================================================
// HALAMAN YANG SUDAH LOGIN
// =====================================================

Route::middleware('auth')->group(function () {

    
    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    // KATEGORI ASET
    Route::get('/kategori', [KategoriAsetController::class, 'index'])
        ->name('kategori.index');

    Route::get('/kategori/create', [KategoriAsetController::class, 'create'])
        ->name('kategori.create');

    Route::post('/kategori', [KategoriAsetController::class, 'store'])
        ->name('kategori.store');

    Route::get('/kategori/{kategori}/edit', [KategoriAsetController::class, 'edit'])
        ->name('kategori.edit');

    Route::put('/kategori/{kategori}', [KategoriAsetController::class, 'update'])
        ->name('kategori.update');

    Route::delete('/kategori/{kategori}', [KategoriAsetController::class, 'destroy'])
        ->name('kategori.destroy');


    
    // SUB KATEGORI ASET
    Route::post('/sub-kategori', [SubKategoriAsetController::class, 'store'])
        ->name('sub-kategori.store');

    Route::put('/sub-kategori/{id}', [SubKategoriAsetController::class, 'update'])
        ->name('sub-kategori.update');

    Route::delete('/sub-kategori/{id}', [SubKategoriAsetController::class, 'destroy'])
        ->name('sub-kategori.destroy');


    
    // JENIS ASET
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

});