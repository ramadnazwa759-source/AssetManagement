<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanAset;
use App\Models\DetailPeminjaman;
use App\Models\DetailUnitPeminjaman;
use App\Models\JenisAset;
use App\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PeminjamanAsetController extends Controller
{
    // Menampilkan daftar peminjaman
    public function index(Request $request)
    {
        $query = PeminjamanAset::with([
            'detailPeminjaman.jenis'
        ]);

        // Mencari transaksi peminjaman
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'kode_peminjaman',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'nama_peminjam',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'detailPeminjaman.jenis',
                    function ($jenis) use ($search) {
                        $jenis->where(
                            'nama_jenis',
                            'like',
                            "%{$search}%"
                        );
                    }
                );
            });
        }

}
