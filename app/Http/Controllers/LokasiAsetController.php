<?php

namespace App\Http\Controllers;

use App\Models\LokasiAset;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class LokasiAsetController extends Controller
{
    /**
     * Menampilkan daftar lokasi aset dan pencarian.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        // VR-AM-LOK-016
        // Panjang kata kunci pencarian maksimal 100 karakter.
        if (mb_strlen($search) > 100) {
            $search = mb_substr($search, 0, 100);
        }

        $query = LokasiAset::query();

        // VR-AM-LOK-002
        // Data hanya diambil dari database.
        if ($search !== '') {
            $query->where(
                'nama_lokasi',
                'like',
                '%' . $search . '%'
            );
        }

        $lokasi = $query
            ->orderBy('nama_lokasi')
            ->get();

        return view('lokasi.index', compact('lokasi', 'search'));
    }

    /**
     * Menampilkan form tambah lokasi.
     */
    public function create()
    {
        $lastLokasi = LokasiAset::where(
            'id_lokasi',
            'like',
            'LOK-%'
        )
            ->orderByRaw(
                'CAST(SUBSTRING(id_lokasi, 5) AS UNSIGNED) DESC'
            )
            ->first();

        if ($lastLokasi) {
            $nomorTerakhir = (int) substr(
                $lastLokasi->id_lokasi,
                4
            );

            $nomorBaru = $nomorTerakhir + 1;
        } else {
            $nomorBaru = 1;
        }

        $idLokasiBaru = 'LOK-' . str_pad(
            $nomorBaru,
            3,
            '0',
            STR_PAD_LEFT
        );

        return view(
            'lokasi.create',
            compact('idLokasiBaru')
        );
    }

    /**
     * Menyimpan lokasi baru.
     */
    public function store(Request $request)
    {
        // Trim terlebih dahulu sebelum validasi.
        $namaLokasi = trim(
            (string) $request->input('nama_lokasi')
        );

        $request->merge([
            'nama_lokasi' => $namaLokasi,
        ]);

        $request->validate([
            // VR-AM-LOK-003
            'nama_lokasi' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/[\pL\pN]/u',
                Rule::unique(
                    'lokasi_aset',
                    'nama_lokasi'
                ),
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],
        ], [
            'nama_lokasi.required' =>
                'Nama lokasi wajib diisi.',

            'nama_lokasi.string' =>
                'Nama lokasi harus berupa teks.',

            'nama_lokasi.min' =>
                'Nama lokasi minimal 3 karakter.',

            'nama_lokasi.max' =>
                'Nama lokasi maksimal 100 karakter.',

            'nama_lokasi.regex' =>
                'Nama lokasi harus memiliki karakter yang bermakna.',

            'nama_lokasi.unique' =>
                'Nama lokasi tersebut sudah digunakan.',
        ]);

        /*
         * Membuat ID lokasi otomatis dengan format:
         *
         * LOK-001
         * LOK-002
         * LOK-003
         * dan seterusnya.
         *
         * Nomor berikutnya diambil dari ID lokasi
         * terbesar yang sudah tersimpan.
         */
        $lastLokasi = LokasiAset::where(
            'id_lokasi',
            'like',
            'LOK-%'
        )
            ->orderByRaw(
                'CAST(SUBSTRING(id_lokasi, 5) AS UNSIGNED) DESC'
            )
            ->first();

        if ($lastLokasi) {
            $nomorTerakhir = (int) substr(
                $lastLokasi->id_lokasi,
                4
            );

            $nomorBaru = $nomorTerakhir + 1;
        } else {
            $nomorBaru = 1;
        }

        $idLokasi = 'LOK-' . str_pad(
            $nomorBaru,
            3,
            '0',
            STR_PAD_LEFT
        );

        LokasiAset::create([
            'id_lokasi' => $idLokasi,
            'nama_lokasi' => $namaLokasi,
            'deskripsi' => $request->input('deskripsi'),
        ]);

        return redirect()
            ->route('lokasi.index')
            ->with(
                'success',
                'Lokasi berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail lokasi.
     */
    public function show($id)
    {
        // VR-AM-LOK-020
        $lokasi = LokasiAset::findOrFail($id);

        return view(
            'lokasi.show',
            compact('lokasi')
        );
    }

    /**
     * Menampilkan form edit lokasi.
     */
    public function edit($id)
    {
        // VR-AM-LOK-011
        $lokasi = LokasiAset::findOrFail($id);

        return view(
            'lokasi.update',
            compact('lokasi')
        );
    }

    /**
     * Mengubah data lokasi.
     */
    public function update(Request $request, $id)
    {
        // VR-AM-LOK-011
        // Pastikan lokasi yang akan diubah masih tersedia.
        $lokasi = LokasiAset::findOrFail($id);

        $namaLokasi = trim(
            (string) $request->input('nama_lokasi')
        );

        $request->merge([
            'nama_lokasi' => $namaLokasi,
        ]);

        $request->validate([
            // VR-AM-LOK-012
            'nama_lokasi' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/[\pL\pN]/u',

                // VR-AM-LOK-013
                // Lokasi yang sedang diedit dikecualikan
                // dari pemeriksaan unique.
                Rule::unique(
                    'lokasi_aset',
                    'nama_lokasi'
                )->ignore(
                    $lokasi->id_lokasi,
                    'id_lokasi'
                ),
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],
        ], [
            'nama_lokasi.required' =>
                'Nama lokasi wajib diisi.',

            'nama_lokasi.string' =>
                'Nama lokasi harus berupa teks.',

            'nama_lokasi.min' =>
                'Nama lokasi minimal 3 karakter.',

            'nama_lokasi.max' =>
                'Nama lokasi maksimal 100 karakter.',

            'nama_lokasi.regex' =>
                'Nama lokasi harus memiliki karakter yang bermakna.',

            'nama_lokasi.unique' =>
                'Nama lokasi tersebut sudah digunakan.',
        ]);

        $lokasi->update([
            'nama_lokasi' => $namaLokasi,
            'deskripsi' => $request->input('deskripsi'),
        ]);

        return redirect()
            ->route('lokasi.index')
            ->with(
                'success',
                'Lokasi berhasil diubah.'
            );
    }

    /**
     * Menghapus lokasi.
     */
    public function destroy($id)
    {
        // VR-AM-LOK-020
        $lokasi = LokasiAset::findOrFail($id);

        /*
         * Penghapusan hanya dilakukan pada Master Lokasi.
         *
         * Tidak ada:
         * - delete() terhadap Data Aset
         * - delete() terhadap Jenis Aset
         * - delete() terhadap data lain
         *
         * Data Aset harus tetap dipertahankan sesuai:
         * BR-AM-LOK-016
         * BR-AM-LOK-017
         * VR-AM-LOK-021
         * VR-AM-LOK-023
         */

        DB::transaction(function () use ($lokasi) {
            $lokasi->delete();
        });

        return redirect()
            ->route('lokasi.index')
            ->with(
                'success',
                'Lokasi berhasil dihapus. Data aset yang sebelumnya menggunakan lokasi tersebut tetap dipertahankan.'
            );
    }
}