<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\LokasiAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
     // Menampilkan daftar aset
    public function index(Request $request)
    {
        $query = Aset::with([
            'jenis',
            'lokasi'
        ]);

        // Mencari aset
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('kode_aset', 'like', "%{$search}%")
                    ->orWhere('nama_aset', 'like', "%{$search}%")
                    ->orWhereHas('jenis', function ($jenis) use ($search) {
                        $jenis->where(
                            'nama_jenis',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        // Memfilter berdasarkan jenis aset
        if ($request->filled('id_jenis')) {
            $query->where(
                'id_jenis',
                $request->id_jenis
            );
        }

        // Memfilter berdasarkan lokasi
        if ($request->filled('id_lokasi')) {
            $query->where(
                'id_lokasi',
                $request->id_lokasi
            );
        }

        // Memfilter berdasarkan kondisi
        if ($request->filled('kondisi_aset')) {
            $query->where(
                'kondisi_aset',
                $request->kondisi_aset
            );
        }

        // Memfilter berdasarkan status
        if ($request->filled('status_aset')) {
            $query->where(
                'status_aset',
                $request->status_aset
            );
        }

        $aset = $query
            ->latest()
            ->get();

        $lokasi = LokasiAset::all();

        return view(
            'asset-management.aset.index',
            compact('aset', 'lokasi')
        );
    }

}
