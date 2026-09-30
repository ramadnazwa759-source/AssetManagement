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

        // Memfilter status peminjaman
        if ($request->filled('status_peminjaman')) {
            $query->where(
                'status_peminjaman',
                $request->status_peminjaman
            );
        }

        $peminjaman = $query
            ->latest()
            ->get();

        return view(
            'asset-management.peminjaman.index',
            compact('peminjaman')
        );
    }

     // Menampilkan form tambah peminjaman
    public function create()
    {
        $jenis = JenisAset::where(
            'status_jenis',
            'Aktif'
        )
            ->withCount([
                'aset as stok_tersedia' => function ($query) {
                    $query->where(
                        'kondisi_aset',
                        'Baik'
                    )
                    ->where(
                        'status_aset',
                        'Tersedia'
                    );
                }
            ])
            ->get();

        return view(
            'asset-management.peminjaman.create',
            compact('jenis')
        );
    }


    // Menyimpan transaksi peminjaman
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' =>
                'required|string|max:100',

            'tanggal_pinjam' =>
                'required|date',

            'tanggal_pengembalian' =>
                'nullable|date|after_or_equal:tanggal_pinjam',

            'tujuan' =>
                'required|string',

            'catatan' =>
                'nullable|string',

            'detail' =>
                'required|array|min:1',

            'detail.*.id_jenis' =>
                'required|exists:jenis_aset,id_jenis',

            'detail.*.jumlah_pinjam' =>
                'required|integer|min:1',
        ]);

        // Memastikan jenis aset tidak muncul dua kali
        $idJenis = collect($validated['detail'])
            ->pluck('id_jenis');

        if ($idJenis->count() !== $idJenis->unique()->count()) {
            throw ValidationException::withMessages([
                'detail' =>
                    'Jenis aset yang sama tidak boleh ditambahkan dua kali.'
            ]);
        }

        DB::transaction(function () use ($validated) {

            // Membuat kode peminjaman
            $kodePeminjaman = $this->generateKodePeminjaman();

            // Menyimpan transaksi peminjaman
            $peminjaman = PeminjamanAset::create([
                'kode_peminjaman' =>
                    $kodePeminjaman,

                'nama_peminjam' =>
                    $validated['nama_peminjam'],

                'tanggal_pinjam' =>
                    $validated['tanggal_pinjam'],

                'tujuan' =>
                    $validated['tujuan'],

                'tanggal_pengembalian' =>
                    $validated['tanggal_pengembalian'] ?? null,

                'jumlah_dikembalikan' =>
                    0,

                'status_peminjaman' =>
                    'Dipinjam',

                'catatan' =>
                    $validated['catatan'] ?? null,
            ]);

            foreach ($validated['detail'] as $item) {

                // Memastikan jenis aset masih aktif
                $jenis = JenisAset::where(
                    'id_jenis',
                    $item['id_jenis']
                )
                    ->where(
                        'status_jenis',
                        'Aktif'
                    )
                    ->first();

                if (!$jenis) {
                    throw ValidationException::withMessages([
                        'detail' =>
                            'Jenis aset yang dipilih tidak aktif.'
                    ]);
                }

                $jumlah = $item['jumlah_pinjam'];

                // Mengunci unit yang tersedia
                $asetTersedia = Aset::where(
                    'id_jenis',
                    $jenis->id_jenis
                )
                    ->where(
                        'kondisi_aset',
                        'Baik'
                    )
                    ->where(
                        'status_aset',
                        'Tersedia'
                    )
                    ->lockForUpdate()
                    ->limit($jumlah)
                    ->get();

                // Menolak seluruh transaksi jika stok kurang
                if ($asetTersedia->count() < $jumlah) {

                    throw ValidationException::withMessages([
                        'detail' =>
                            "Stok {$jenis->nama_jenis} tidak mencukupi. " .
                            "Tersedia {$asetTersedia->count()} unit."
                    ]);
                }

                // Menyimpan detail jenis
                $detail = DetailPeminjaman::create([
                    'id_peminjaman' =>
                        $peminjaman->id_peminjaman,

                    'id_jenis' =>
                        $jenis->id_jenis,

                    'jumlah_pinjam' =>
                        $jumlah,

                    'jumlah_dikembalikan' =>
                        0,
                ]);

                foreach ($asetTersedia as $aset) {

                    // Menyimpan unit yang dialokasikan
                    DetailUnitPeminjaman::create([
                        'id_detail_peminjaman' =>
                            $detail->id_detail_peminjaman,

                        'kode_aset' =>
                            $aset->kode_aset,

                        'status_unit' =>
                            'Dipinjam',
                    ]);

                    // Mengubah status unit menjadi dipinjam
                    $aset->update([
                        'status_aset' =>
                            'Dipinjam'
                    ]);
                }
            }
        });

        return redirect()
            ->route('peminjaman.index')
            ->with(
                'success',
                'Transaksi peminjaman berhasil disimpan.'
            );
    }
}
