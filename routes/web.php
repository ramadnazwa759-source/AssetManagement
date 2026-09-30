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