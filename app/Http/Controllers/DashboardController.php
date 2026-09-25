<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\SubKategoriAset;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil semua sub kategori untuk tampilan dashboard
        $subKategori = SubKategoriAset::all();

        // Menghitung seluruh aset
        $totalAset = Aset::count();

        // Menghitung aset yang tersedia
        $asetTersedia = Aset::where('status_aset', 'Tersedia')->count();

        // Menghitung aset yang sedang dipinjam
        $asetDipinjam = Aset::where('status_aset', 'Dipinjam')->count();

        // Menghitung aset yang perlu diperbaiki
        $perluPerbaikan = Aset::whereIn('kondisi_aset', [
            'Rusak Ringan',
            'Rusak Berat'
        ])->count();

        return view('index', compact(
            'subKategori',
            'totalAset',
            'asetTersedia',
            'asetDipinjam',
            'perluPerbaikan'
        ));
    }
}
