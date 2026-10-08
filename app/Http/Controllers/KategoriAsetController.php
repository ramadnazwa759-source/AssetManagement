<?php

namespace App\Http\Controllers;

use App\Models\KategoriAset;
use App\Models\SubKategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KategoriAsetController extends Controller
{
    // =====================================================
    // MENAMPILKAN SEMUA KATEGORI + SEARCH
    // =====================================================

    public function index(Request $request)
    {
        /*
         * FR-AM-KAT-001
         * BR-AM-KAT-001 s.d. BR-AM-KAT-003
         * VR-AM-KAT-001 s.d. VR-AM-KAT-002
         *
         * Data kategori diambil langsung dari database.
         */

        $query = KategoriAset::query();

        /*
         * FR-AM-KAT-004
         * BR-AM-KAT-009 s.d. BR-AM-KAT-0010
         * VR-AM-KAT-009 s.d. VR-AM-KAT-010
         *
         * Search berdasarkan data kategori yang tersimpan.
         */
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            /*
             * VR-AM-KAT-009
             * Kata kunci digunakan sebagai nilai pencarian,
             * bukan sebagai bagian dari query SQL.
             *
             * VR-AM-KAT-010
             * Batas panjang kata kunci maksimal 100 karakter.
             */
            if (mb_strlen($search) > 100) {
                $search = mb_substr($search, 0, 100);
            }

            if ($search !== '') {
                $query->where(
                    'nama_kategori',
                    'like',
                    '%' . $search . '%'
                );
            }
        }

        $kategori = $query->get();

        /*
         * Tetap mengambil Subkategori karena digunakan
         * pada tampilan halaman kategori.
         */
        $subKategori = SubKategoriAset::all();

        return view(
            'kategori.index',
            compact(
                'kategori',
                'subKategori'
            )
        );
    }


    // =====================================================
    // FORM TAMBAH KATEGORI
    // =====================================================

    public function create()
    {
        /*
         * Kode kategori dibuat oleh sistem.
         * Admin tidak menentukan kode secara manual.
         */

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
        /*
         * VR-AM-KAT-011
         *
         * Proses penambahan hanya boleh dilakukan
         * oleh Admin yang telah terautentikasi.
         *
         * Pemeriksaan utama tetap dilakukan oleh
         * middleware Asset Management.
         */
        if (!$request->session()->get('asset_management_authenticated')) {
            return redirect()->route('auth.login');
        }

        /*
         * Ambil dan bersihkan nama kategori terlebih dahulu.
         *
         * VR-AM-KAT-004
         * Tidak boleh kosong.
         *
         * VR-AM-KAT-005
         * Nama kategori harus unik.
         */
        $namaKategori = trim(
            $request->input('nama_kategori', '')
        );

        $validator = Validator::make(
            [
                'nama_kategori' => $namaKategori,
                'deskripsi' => $request->input('deskripsi'),
            ],
            [
                'nama_kategori' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    Rule::unique(
                        'kategori_aset',
                        'nama_kategori'
                    ),
                    function ($attribute, $value, $fail) {
                        /*
                         * VR-AM-KAT-004
                         *
                         * Nama tidak boleh hanya terdiri
                         * dari simbol atau karakter khusus.
                         */
                        if (!preg_match('/[A-Za-z0-9]/', $value)) {
                            $fail(
                                'Nama kategori harus memiliki karakter yang bermakna.'
                            );
                        }
                    },
                ],
                'deskripsi' => [
                    'nullable',
                    'string',
                ],
            ]
        );

        $validator->validate();

        /*
         * Generate ID kategori di sisi server.
         * Admin tidak menentukan ID kategori.
         */
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

        /*
         * Simpan kategori yang sudah lolos validasi.
         */
        KategoriAset::create([
            'id_kategori' => $idKategori,
            'nama_kategori' => $namaKategori,
            'deskripsi' => $request->input('deskripsi'),
        ]);

        return redirect()
            ->route('kategori.index')
            ->with(
                'success',
                'Kategori berhasil ditambahkan.'
            );
    }


    // =====================================================
    // MENAMPILKAN DETAIL KATEGORI
    // =====================================================

    public function show($id)
    {
        /*
         * Pastikan kategori memang tersedia
         * pada database.
         */
        $kategori = KategoriAset::findOrFail($id);

        /*
         * Menampilkan Subkategori yang memiliki
         * kategori tersebut sebagai induk.
         */
        $subKategori = SubKategoriAset::where(
            'id_kategori',
            $kategori->id_kategori
        )->get();

        return view(
            'kategori.show',
            compact(
                'kategori',
                'subKategori'
            )
        );
    }


    // =====================================================
    // FORM UBAH KATEGORI
    // =====================================================

    public function edit($id)
    {
        /*
         * Pastikan data yang akan diubah tersedia
         * pada database.
         *
         * VR-AM-KAT-007
         */
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
        /*
         * VR-AM-KAT-011
         *
         * Proses perubahan hanya boleh dilakukan
         * oleh Admin yang telah terautentikasi.
         */
        if (!$request->session()->get('asset_management_authenticated')) {
            return redirect()->route('auth.login');
        }

        /*
         * VR-AM-KAT-007
         *
         * Pastikan kategori yang akan diubah
         * benar-benar tersedia di database.
         */
        $kategori = KategoriAset::findOrFail($id);

        /*
         * Bersihkan input terlebih dahulu.
         *
         * VR-AM-KAT-006
         * Data hasil perubahan harus divalidasi.
         */
        $namaKategori = trim(
            $request->input('nama_kategori', '')
        );

        $validator = Validator::make(
            [
                'nama_kategori' => $namaKategori,
                'deskripsi' => $request->input('deskripsi'),
            ],
            [
                'nama_kategori' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',

                    /*
                     * VR-AM-KAT-005
                     *
                     * Nama kategori harus unik,
                     * tetapi kategori yang sedang diedit
                     * tidak dianggap sebagai duplikat.
                     */
                    Rule::unique(
                        'kategori_aset',
                        'nama_kategori'
                    )->ignore(
                        $kategori->id_kategori,
                        'id_kategori'
                    ),

                    function ($attribute, $value, $fail) {
                        /*
                         * Nama tidak boleh hanya berisi
                         * simbol/karakter khusus.
                         */
                        if (!preg_match('/[A-Za-z0-9]/', $value)) {
                            $fail(
                                'Nama kategori harus memiliki karakter yang bermakna.'
                            );
                        }
                    },
                ],
                'deskripsi' => [
                    'nullable',
                    'string',
                ],
            ]
        );

        $validator->validate();

        /*
         * Hanya data yang diperbolehkan yang diubah.
         * ID kategori tetap.
         */
        $kategori->update([
            'nama_kategori' => $namaKategori,
            'deskripsi' => $request->input('deskripsi'),
        ]);

        return redirect()
            ->route('kategori.index')
            ->with(
                'success',
                'Kategori berhasil diubah.'
            );
    }
}