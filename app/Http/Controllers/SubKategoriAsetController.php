<?php

namespace App\Http\Controllers;

use App\Models\SubKategoriAset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;

class SubKategoriAsetController extends Controller
{
    // Menampilkan sub kategori berdasarkan kategori
    public function index($id_kategori)
    {
        $kategori = KategoriAset::findOrFail($id_kategori);

        $subKategori = SubKategoriAset::where(
            'id_kategori',
            $id_kategori
        )->get();

        return view('sub-kategori.index', compact(
            'kategori',
            'subKategori'
        ));
    }


    // Menampilkan form tambah sub kategori
    public function create($id_kategori)
    {
        $kategori = KategoriAset::findOrFail($id_kategori);

        // Membuat ID sub kategori berikutnya
        $nomorTerakhir = SubKategoriAset::all()
            ->map(function ($item) {
                return (int) str_replace(
                    'SUB-',
                    '',
                    $item->id_sub_kategori
                );
            })
            ->max() ?? 0;

        $idSubKategoriBaru = 'SUB-' . str_pad(
            $nomorTerakhir + 1,
            2,
            '0',
            STR_PAD_LEFT
        );

        return view('sub-kategori.create', compact(
            'kategori',
            'idSubKategoriBaru'
        ));
    }


    // Menyimpan sub kategori
    public function store(Request $request, $id_kategori)
    {
        // Pastikan kategori yang dipilih memang ada
        $kategori = KategoriAset::findOrFail($id_kategori);

        $request->validate([
            'nama_sub_kategori' => 'required|string|max:100',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // Membuat ID sub kategori berikutnya
        $nomorTerakhir = SubKategoriAset::all()
            ->map(function ($item) {
                return (int) str_replace(
                    'SUB-',
                    '',
                    $item->id_sub_kategori
                );
            })
            ->max() ?? 0;

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


    // Menampilkan form edit sub kategori
    public function edit($id_kategori, $id)
    {
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

        return view('sub-kategori.edit', compact(
            'kategori',
            'subKategori'
        ));
    }


    // Mengubah sub kategori
    public function update(
        Request $request,
        $id_kategori,
        $id
    ) {
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


    // Menghapus sub kategori
    public function destroy($id_kategori, $id)
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

        // Hapus sub kategori
        $subKategori->delete();

        return redirect()
            ->route(
                'kategori.sub-kategori.index',
                $id_kategori
            )
            ->with(
                'success',
                'Sub kategori berhasil dihapus.'
            );
    }
}