<?php

namespace App\Http\Controllers;

use App\Models\JenisAset;
use App\Models\Aset;
use App\Models\SubKategoriAset;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisAsetController extends Controller
{
    // Menampilkan daftar jenis aset
    public function index()
    {
        $jenis = JenisAset::with('subKategori')
            ->latest()
            ->get();

        return view(
            'asset-management.jenis.index',
            compact('jenis')
        );
    }

     // Menampilkan form tambah jenis aset
    public function create()
    {
        $subKategori = SubKategoriAset::all();

        return view(
            'asset-management.jenis.create',
            compact('subKategori')
        );
    }
}
