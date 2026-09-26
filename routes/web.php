<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisAsetController;
use App\Http\Controllers\AsetController;

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
