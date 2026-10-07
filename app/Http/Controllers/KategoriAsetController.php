<?php

namespace App\Http\Controllers;

use App\Models\KategoriAset;
use App\Models\SubKategoriAset;
use Illuminate\Http\Request;

class KategoriAsetController extends Controller
{
    // =====================================================
    // MENAMPILKAN SEMUA KATEGORI + SEARCH
    // =====================================================

    public function index(Request $request)
    {
        // Query kategori
        $query = KategoriAset::query();

        // Pencarian berdasarkan nama kategori
        if ($request->filled('search')) {
            $query->where(
                'nama_kategori',
                'like',
                '%' . $request->search . '%'
            );
        }

        // Ambil data kategori
        $kategori = $query->get();

        // Ambil semua sub kategori
        $subKategori = SubKategoriAset::all();

        return view(
            'kategori.index',
            compact('kategori', 'subKategori')
        );
    }

    // =====================================================
    // FORM TAMBAH KATEGORI
    // =====================================================

    public function create()
    {
        $nomorTerakhir = KategoriAset::all()
            ->map(function ($item) {
                return (int) str_replace(
                    'KAT-',
                    '',
                    $item->id_kategori
                );
            })
            ->max() ?? 0;

        $idKategoriBaru = 'KAT-' . str_pad(
            $nomorTerakhir + 1,
            2,
            '0',
            STR_PAD_LEFT
        );

        return view(
            'kategori.create',
            compact('idKategoriBaru')
        );
    }

    // =====================================================
    // MENYIMPAN KATEGORI BARU
    // =====================================================

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_aset,nama_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        $nomorTerakhir = KategoriAset::all()
            ->map(function ($item) {
                return (int) str_replace(
                    'KAT-',
                    '',
                    $item->id_kategori
                );
            })
            ->max() ?? 0;

        $idKategori = 'KAT-' . str_pad(
            $nomorTerakhir + 1,
            2,
            '0',
            STR_PAD_LEFT
        );

        KategoriAset::create([
            'id_kategori' => $idKategori,
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // =====================================================
    // MENAMPILKAN DETAIL KATEGORI
    // =====================================================

    public function show($id)
    {
        $kategori = KategoriAset::findOrFail($id);

        $subKategori = SubKategoriAset::where(
            'id_kategori',
            $kategori->id_kategori
        )->get();

        return view(
            'kategori.show',
            compact('kategori', 'subKategori')
        );
    }

    // =====================================================
    // FORM UBAH KATEGORI
    // =====================================================

    public function edit($id)
    {
        $kategori = KategoriAset::findOrFail($id);

        return view(
            'kategori.update',
            compact('kategori')
        );
    }

    // =====================================================
    // UPDATE / MENGUBAH KATEGORI
    // =====================================================

    public function update(Request $request, $id)
    {
        $kategori = KategoriAset::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_aset,nama_kategori,' . $id . ',id_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil diubah.');
    }
}