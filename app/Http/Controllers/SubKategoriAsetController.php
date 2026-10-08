<?php

namespace App\Http\Controllers;

use App\Models\SubKategoriAset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;

class SubKategoriAsetController extends Controller
{
    // =====================================================
    // MENAMPILKAN SUB KATEGORI BERDASARKAN KATEGORI + SEARCH
    // =====================================================

    public function index(Request $request, $id_kategori)
    {
        // Pastikan kategori induknya ada
        $kategori = KategoriAset::findOrFail($id_kategori);

        // Query sub kategori berdasarkan kategori induk
        $query = SubKategoriAset::where(
            'id_kategori',
            $id_kategori
        );

        // Pencarian berdasarkan nama sub kategori
        if ($request->filled('search')) {
            $query->where(
                'nama_sub_kategori',
                'like',
                '%' . $request->search . '%'
            );
        }

        // Ambil data sub kategori
        $subKategori = $query->get();

        return view(
            'sub-kategori.index',
            compact('kategori', 'subKategori')
        );
    }


    // =====================================================
    // MENAMPILKAN DETAIL SUB KATEGORI
    // =====================================================

    public function show($id_kategori, $id)
    {
        // Pastikan kategori induknya ada
        $kategori = KategoriAset::findOrFail($id_kategori);

        // Cari sub kategori berdasarkan ID dan kategori induknya
        $subKategori = SubKategoriAset::where(
            'id_sub_kategori',
            $id
        )
        ->where(
            'id_kategori',
            $id_kategori
        )
        ->firstOrFail();

        return view(
            'sub-kategori.show',
            compact('kategori', 'subKategori')
        );
    }


    // =====================================================
    // FORM TAMBAH SUB KATEGORI
    // =====================================================

    public function create($id_kategori)
    {
        // Pastikan kategori induknya ada
        $kategori = KategoriAset::findOrFail($id_kategori);

        // Mencari nomor ID sub kategori terakhir
        $nomorTerakhir = SubKategoriAset::all()
            ->map(function ($item) {
                return (int) str_replace(
                    'SUB-',
                    '',
                    $item->id_sub_kategori
                );
            })
            ->max() ?? 0;

        // Membuat ID sub kategori baru
        $idSubKategoriBaru = 'SUB-' . str_pad(
            $nomorTerakhir + 1,
            2,
            '0',
            STR_PAD_LEFT
        );

        return view(
            'sub-kategori.create',
            compact('kategori', 'idSubKategoriBaru')
        );
    }


    // =====================================================
    // MENYIMPAN SUB KATEGORI
    // =====================================================

    public function store(Request $request, $id_kategori)
    {
        // Pastikan kategori induknya ada
        $kategori = KategoriAset::findOrFail($id_kategori);

        // Validasi data
        $request->validate([
            'nama_sub_kategori' => 'required|string|max:100',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // Mencari nomor ID sub kategori terakhir
        $nomorTerakhir = SubKategoriAset::all()
            ->map(function ($item) {
                return (int) str_replace(
                    'SUB-',
                    '',
                    $item->id_sub_kategori
                );
            })
            ->max() ?? 0;

        // Membuat ID sub kategori baru
        $idSubKategori = 'SUB-' . str_pad(
            $nomorTerakhir + 1,
            2,
            '0',
            STR_PAD_LEFT
        );

        // Upload gambar jika ada
        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')
                ->store('sub-kategori', 'public');
        }

        // Simpan data sub kategori
        SubKategoriAset::create([
            'id_sub_kategori' => $idSubKategori,
            'id_kategori' => $id_kategori,
            'nama_sub_kategori' => $request->nama_sub_kategori,
            'gambar' => $gambar,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()
            ->route(
                'kategori.sub-kategori.index',
                $id_kategori
            )
            ->with(
                'success',
                'Sub kategori berhasil ditambahkan.'
            );
    }


    // =====================================================
    // FORM EDIT SUB KATEGORI
    // =====================================================

    public function edit($id_kategori, $id)
    {
        // Pastikan kategori induknya ada
        $kategori = KategoriAset::findOrFail($id_kategori);

        // Cari sub kategori berdasarkan kategori + ID
        $subKategori = SubKategoriAset::where(
            'id_sub_kategori',
            $id
        )
        ->where(
            'id_kategori',
            $id_kategori
        )
        ->firstOrFail();

        return view(
            'sub-kategori.edit',
            compact('kategori', 'subKategori')
        );
    }


    // =====================================================
    // UPDATE / MENGUBAH SUB KATEGORI
    // =====================================================

    public function update(
        Request $request,
        $id_kategori,
        $id
    ) {
        // Pastikan kategori induknya ada
        $kategori = KategoriAset::findOrFail($id_kategori);

        // Cari sub kategori berdasarkan kategori + ID
        $subKategori = SubKategoriAset::where(
            'id_sub_kategori',
            $id
        )
        ->where(
            'id_kategori',
            $id_kategori
        )
        ->firstOrFail();

        // Validasi
        $request->validate([
            'nama_sub_kategori' => 'required|string|max:100',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $data = [
            'nama_sub_kategori' => $request->nama_sub_kategori,
            'deskripsi' => $request->deskripsi,
        ];

        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')
                ->store('sub-kategori', 'public');
        }

        // Update data
        $subKategori->update($data);

        return redirect()
            ->route(
                'kategori.sub-kategori.index',
                $id_kategori
            )
            ->with(
                'success',
                'Sub kategori berhasil diubah.'
            );
    }


    // =====================================================
    // MENGAMBIL SUB KATEGORI BERDASARKAN KATEGORI
    // Untuk kebutuhan AJAX/API
    // =====================================================

    public function berdasarkanKategori($id_kategori)
    {
        $subKategori = SubKategoriAset::where(
            'id_kategori',
            $id_kategori
        )->get();

        return response()->json($subKategori);
    }
}