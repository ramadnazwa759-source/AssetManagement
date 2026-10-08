<?php

namespace App\Http\Controllers;

use App\Models\SubKategoriAset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;

class SubKategoriAsetController extends Controller
{
    /**
     * Menampilkan Subkategori berdasarkan Kategori Aset yang dipilih.
     * FR-AM-SUB-001
     * FR-AM-SUB-004
     */
    public function index(Request $request, $id_kategori)
    {
        // VR-AM-SUB-001
        // Pastikan kategori induk benar-benar tersedia.
        $kategori = KategoriAset::findOrFail($id_kategori);

        // VR-AM-SUB-002
        // Hanya mengambil Subkategori yang memiliki
        // hubungan dengan kategori yang sedang dipilih.
        $query = SubKategoriAset::where(
            'id_kategori',
            $id_kategori
        );

        // BR-AM-SUB-009
        // Pencarian tetap dibatasi pada kategori yang sedang dipilih.
        if ($request->filled('search')) {

            $search = trim($request->input('search'));

            if ($search !== '') {
                $query->where(
                    'nama_sub_kategori',
                    'like',
                    '%' . $search . '%'
                );
            }
        }

        $subKategori = $query->get();

        // BR-AM-SUB-003
        // Informasi kategori induk dikirim ke halaman.
        return view(
            'sub-kategori.index',
            compact('kategori', 'subKategori')
        );
    }


    /**
     * Menampilkan form tambah Subkategori.
     * FR-AM-SUB-002
     */
    public function create($id_kategori)
    {
        // VR-AM-SUB-003
        // Kategori induk harus tersedia sebelum form ditampilkan.
        $kategori = KategoriAset::findOrFail($id_kategori);

        // ID Subkategori dibuat oleh server.
        $nomorTerakhir = SubKategoriAset::query()
            ->get()
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

        return view(
            'sub-kategori.create',
            compact(
                'kategori',
                'idSubKategoriBaru'
            )
        );
    }


    /**
     * Menyimpan Subkategori baru.
     * FR-AM-SUB-002
     */
    public function store(Request $request, $id_kategori)
    {
        // VR-AM-SUB-003
        // Pastikan kategori induk tersedia.
        $kategori = KategoriAset::findOrFail($id_kategori);

        // VR-AM-SUB-004
        // Validasi data yang berasal dari form.
        $validated = $request->validate([
            'nama_sub_kategori' => [
                'required',
                'string',
                'max:100',
            ],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'deskripsi' => [
                'nullable',
                'string',
            ],
        ]);

        // ID Subkategori dibuat oleh server,
        // bukan diterima dari client.
        $nomorTerakhir = SubKategoriAset::query()
            ->get()
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

        // Upload gambar jika memang dikirim dari form.
        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')
                ->store('sub-kategori', 'public');
        }

        // BR-AM-SUB-005
        // id_kategori ditentukan dari parameter route,
        // bukan dari input client.
        SubKategoriAset::create([
            'id_sub_kategori' => $idSubKategori,
            'id_kategori' => $id_kategori,
            'nama_sub_kategori' => $validated['nama_sub_kategori'],
            'gambar' => $gambar,
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        // BR-AM-SUB-006
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


    /**
     * Menampilkan form edit Subkategori.
     * FR-AM-SUB-003
     */
    public function edit($id_kategori, $id)
{
    $kategori = KategoriAset::findOrFail($id_kategori);

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
        'sub-kategori.update',
        compact('kategori', 'subKategori')
    );
}


    /**
     * Mengubah Subkategori.
     * FR-AM-SUB-003
     */
    public function update(
        Request $request,
        $id_kategori,
        $id
    ) {
        // Pastikan kategori induk tersedia.
        $kategori = KategoriAset::findOrFail($id_kategori);

        // VR-AM-SUB-006
        // Target harus berada pada kategori yang sedang dikelola.
        $subKategori = SubKategoriAset::where(
            'id_sub_kategori',
            $id
        )
            ->where(
                'id_kategori',
                $id_kategori
            )
            ->firstOrFail();

        // VR-AM-SUB-004
        $validated = $request->validate([
            'nama_sub_kategori' => [
                'required',
                'string',
                'max:100',
            ],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'deskripsi' => [
                'nullable',
                'string',
            ],
        ]);

        $data = [
            'nama_sub_kategori' => $validated['nama_sub_kategori'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        // Gambar hanya diperbarui jika Admin
        // mengirim gambar baru.
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')
                ->store('sub-kategori', 'public');
        }

        // BR-AM-SUB-007
        // id_kategori TIDAK dimasukkan ke update,
        // sehingga hubungan dengan kategori induk tetap.
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


    /**
     * Mengambil Subkategori berdasarkan Kategori.
     *
     * Method tambahan untuk kebutuhan data dinamis/API.
     * Bukan bagian langsung dari FR-AM-SUB-001 s.d. FR-AM-SUB-004.
     */
    public function berdasarkanKategori($id_kategori)
    {
        $kategori = KategoriAset::findOrFail($id_kategori);

        $subKategori = SubKategoriAset::where(
            'id_kategori',
            $kategori->id_kategori
        )->get();

        return response()->json($subKategori);
    }
}