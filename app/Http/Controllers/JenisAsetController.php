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

     // Menambahkan jenis aset dan membuat unit aset sesuai stok
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jenis' => 'required|string|max:25|unique:jenis_aset,id_jenis',

            'id_sub_kategori_aset' =>
                'required|exists:sub_kategori_aset,id_sub_kategori',

            'nama_jenis' =>
                'required|string|max:100|unique:jenis_aset,nama_jenis',

            'stok' =>
                'required|integer|min:0',

            'deskripsi' =>
                'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {

            // Menyimpan data jenis aset
            $jenis = JenisAset::create([
                'id_jenis' => $validated['id_jenis'],
                'id_sub_kategori_aset' => $validated['id_sub_kategori_aset'],
                'nama_jenis' => $validated['nama_jenis'],
                'stok' => $validated['stok'],
                'status_jenis' => 'Aktif',
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            // Membuat unit aset sesuai jumlah stok
            for ($i = 1; $i <= $validated['stok']; $i++) {
                Aset::create([
                    'kode_aset' => $this->generateKodeAset(
                        $jenis->nama_jenis,
                        $i
                    ),
                    'id_jenis' => $jenis->id_jenis,
                    'id_lokasi' => null,
                    'nama_aset' => $jenis->nama_jenis,
                    'tanggal_beli' => null,
                    'kondisi_aset' => 'Baik',
                    'status_aset' => 'Tersedia',
                    'gambar' => null,
                    'keterangan' => null,
                ]);
            }
        });

        return redirect()
            ->route('jenis-aset.index')
            ->with(
                'success',
                'Jenis aset dan stok berhasil ditambahkan.'
            );
    }

    // Menampilkan detail jenis aset
    public function show(string $id)
    {
        $jenis = JenisAset::with([
            'subKategori',
            'aset'
        ])->findOrFail($id);

        return view(
            'asset-management.jenis.show',
            compact('jenis')
        );
    }

    // Menampilkan form ubah jenis aset
    public function edit(string $id)
    {
        $jenis = JenisAset::findOrFail($id);

        $subKategori = SubKategoriAset::all();

        return view(
            'asset-management.jenis.edit',
            compact('jenis', 'subKategori')
        );
    }

    // Mengubah data jenis aset
    public function update(Request $request, string $id)
    {
        $jenis = JenisAset::findOrFail($id);

        $validated = $request->validate([
            'id_sub_kategori_aset' =>
                'required|exists:sub_kategori_aset,id_sub_kategori',

            'nama_jenis' =>
                'required|string|max:100|unique:jenis_aset,nama_jenis,' . $id . ',id_jenis',

            'deskripsi' =>
                'nullable|string',
        ]);

        $jenis->update([
            'id_sub_kategori_aset' =>
                $validated['id_sub_kategori_aset'],

            'nama_jenis' =>
                $validated['nama_jenis'],

            'deskripsi' =>
                $validated['deskripsi'] ?? null,
        ]);

        return redirect()
            ->route('jenis-aset.index')
            ->with(
                'success',
                'Jenis aset berhasil diperbarui.'
            );
    }

    // Menambahkan stok dan membuat unit aset baru
    public function addStock(Request $request, string $id)
    {
        $jenis = JenisAset::findOrFail($id);

        $validated = $request->validate([
            'tambah_stok' =>
                'required|integer|min:1',
        ]);

        DB::transaction(function () use ($jenis, $validated) {

            // Menghitung stok baru
            $stokLama = $jenis->stok;
            $tambahStok = $validated['tambah_stok'];
            $stokBaru = $stokLama + $tambahStok;

            $jenis->update([
                'stok' => $stokBaru
            ]);

            // Membuat unit aset tambahan
            for (
                $i = $stokLama + 1;
                $i <= $stokBaru;
                $i++
            ) {
                Aset::create([
                    'kode_aset' => $this->generateKodeAset(
                        $jenis->nama_jenis,
                        $i
                    ),
                    'id_jenis' => $jenis->id_jenis,
                    'id_lokasi' => null,
                    'nama_aset' => $jenis->nama_jenis,
                    'tanggal_beli' => null,
                    'kondisi_aset' => 'Baik',
                    'status_aset' => 'Tersedia',
                    'gambar' => null,
                    'keterangan' => null,
                ]);
            }
        });

        return redirect()
            ->route('jenis-aset.index')
            ->with(
                'success',
                'Stok aset berhasil ditambahkan.'
            );
    }

}
