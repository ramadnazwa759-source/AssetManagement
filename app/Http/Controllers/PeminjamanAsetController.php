<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanAset;
use App\Models\DetailPeminjaman;
use App\Models\DetailUnitPeminjaman;
use App\Models\JenisAset;
use App\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PeminjamanAsetController extends Controller
{
    /**
     * Menampilkan daftar transaksi peminjaman.
     * Mendukung pencarian, filter tanggal, dan filter status.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],
            'tanggal_mulai' => [
                'nullable',
                'date',
            ],
            'tanggal_akhir' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'status_peminjaman' => [
                'nullable',
                Rule::in([
                    'Dipinjam',
                    'Dikembalikan',
                    'Dibatalkan',
                ]),
            ],
        ]);

        $query = PeminjamanAset::with([
            'detailPeminjaman.jenis',
        ]);

        // Pencarian berdasarkan kode, nama peminjam,
        // dan nama jenis aset.
        if (!empty($validated['search'])) {
            $search = trim($validated['search']);

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

        // Filter berdasarkan rentang tanggal peminjaman.
        if (!empty($validated['tanggal_mulai'])) {
            $query->whereDate(
                'tanggal_pinjam',
                '>=',
                $validated['tanggal_mulai']
            );
        }

        if (!empty($validated['tanggal_akhir'])) {
            $query->whereDate(
                'tanggal_pinjam',
                '<=',
                $validated['tanggal_akhir']
            );
        }

        // Filter berdasarkan status transaksi.
        if (!empty($validated['status_peminjaman'])) {
            $query->where(
                'status_peminjaman',
                $validated['status_peminjaman']
            );
        }

        $peminjaman = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'peminjaman.index',
            compact('peminjaman')
        );
    }


    /**
     * Menampilkan form tambah peminjaman.
     * Hanya menampilkan jenis aset aktif beserta stok tersedia.
     */
    public function create()
{
    $jenisAset = JenisAset::where('status_jenis', 'Aktif')
        ->withCount([
            'aset as stok_tersedia' => function ($query) {
                $query->where('kondisi_aset', 'Baik')
                    ->where('status_aset', 'Tersedia');
            }
        ])
        ->get();

    return view(
        'peminjaman.create',
        compact('jenisAset')
    );
}


    /**
     * Menyimpan transaksi peminjaman.
     * Unit aset dipilih otomatis oleh sistem.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' => [
                'required',
                'string',
                'max:100',
                'regex:/\S/',
            ],

            'tanggal_pinjam' => [
                'required',
                'date',
            ],

            'tanggal_pengembalian' => [
                'required',
                'date',
                'after_or_equal:tanggal_pinjam',
            ],

            'tujuan' => [
                'required',
                'string',
                'max:255',
                'regex:/\S/',
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'detail' => [
                'required',
                'array',
                'min:1',
            ],

            'detail.*.id_jenis' => [
                'required',
                'string',
                'exists:jenis_aset,id_jenis',
                
            ],

            'detail.*.jumlah_pinjam' => [
                'required',
                'integer',
                'min:1',
            ],
        ], [
            'nama_peminjam.required' =>
                'Nama peminjam wajib diisi.',

            'nama_peminjam.regex' =>
                'Nama peminjam tidak boleh hanya berisi spasi.',

            'tanggal_pinjam.required' =>
                'Tanggal peminjaman wajib diisi.',

            'tanggal_pengembalian.required' =>
                'Tanggal rencana pengembalian wajib diisi.',

            'tanggal_pengembalian.after_or_equal' =>
                'Tanggal pengembalian tidak boleh sebelum tanggal peminjaman.',

            'tujuan.required' =>
                'Tujuan peminjaman wajib diisi.',

            'tujuan.regex' =>
                'Tujuan peminjaman tidak boleh hanya berisi spasi.',

            'detail.required' =>
                'Minimal pilih satu jenis aset.',

            'detail.min' =>
                'Minimal pilih satu jenis aset.',

            'detail.*.id_jenis.exists' =>
                'Jenis aset yang dipilih tidak ditemukan.',

            'detail.*.jumlah_pinjam.integer' =>
                'Jumlah pinjam harus berupa angka bulat.',

            'detail.*.jumlah_pinjam.min' =>
                'Jumlah pinjam minimal satu unit.',
        ]);

        // Membersihkan spasi pada input teks.
        $validated['nama_peminjam'] =
            trim($validated['nama_peminjam']);

        $validated['tujuan'] =
            trim($validated['tujuan']);

        if (isset($validated['catatan'])) {
            $validated['catatan'] =
                trim($validated['catatan']);

            if ($validated['catatan'] === '') {
                $validated['catatan'] = null;
            }
        }

        // Memastikan jenis aset tidak dimasukkan dua kali.
        $idJenis = collect($validated['detail'])
            ->pluck('id_jenis');

        if (
            $idJenis->count() !==
            $idJenis->unique()->count()
        ) {
            throw ValidationException::withMessages([
                'detail' =>
                    'Jenis aset yang sama tidak boleh ditambahkan dua kali.',
            ]);
        }

        DB::transaction(function () use ($validated) {

            // Mengunci data jenis aset agar proses peminjaman
            // pada jenis yang sama dapat diperiksa secara berurutan.
            $jenisAset = JenisAset::whereIn(
                'id_jenis',
                collect($validated['detail'])
                    ->pluck('id_jenis')
                    ->sort()
                    ->values()
            )
                ->orderBy('id_jenis')
                ->lockForUpdate()
                ->get()
                ->keyBy('id_jenis');

            // Memastikan semua jenis masih aktif.
            foreach ($validated['detail'] as $item) {
                $jenis = $jenisAset->get(
                    $item['id_jenis']
                );

                if (
                    !$jenis ||
                    $jenis->status_jenis !== 'Aktif'
                ) {
                    throw ValidationException::withMessages([
                        'detail' =>
                            'Jenis aset yang dipilih tidak tersedia atau sudah tidak aktif.',
                    ]);
                }
            }

            // Menyimpan transaksi utama.
            $peminjaman = PeminjamanAset::create([
                'kode_peminjaman' =>
                    $this->generateKodePeminjaman(),

                'nama_peminjam' =>
                    $validated['nama_peminjam'],

                'tanggal_pinjam' =>
                    $validated['tanggal_pinjam'],

                'tanggal_pengembalian' =>
                    $validated['tanggal_pengembalian'],

                'tujuan' =>
                    $validated['tujuan'],

                'jumlah_dikembalikan' =>
                    0,

                'status_peminjaman' =>
                    'Dipinjam',

                'catatan' =>
                    $validated['catatan'] ?? null,
            ]);

            foreach ($validated['detail'] as $item) {

                $jenis = $jenisAset->get(
                    $item['id_jenis']
                );

                $jumlah = (int) $item['jumlah_pinjam'];

                // Mengambil dan mengunci unit yang memenuhi syarat.
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
                    ->orderBy('kode_aset')
                    ->lockForUpdate()
                    ->limit($jumlah)
                    ->get();

                // Menolak seluruh transaksi apabila stok kurang.
                if ($asetTersedia->count() < $jumlah) {
                    throw ValidationException::withMessages([
                        'detail' =>
                            "Stok {$jenis->nama_jenis} tidak mencukupi. " .
                            "Jumlah tersedia saat ini {$asetTersedia->count()} unit.",
                    ]);
                }

                // Menyimpan detail jenis aset yang dipinjam.
                $detail = DetailPeminjaman::create([
                    'id_peminjaman' =>
                        $peminjaman->id_peminjaman,

                    'id_jenis' =>
                        $jenis->id_jenis,

                    'jumlah_pinjam' =>
                        $jumlah,
                ]);

                // Menyimpan unit yang dialokasikan oleh sistem.
                foreach ($asetTersedia as $aset) {

                    DetailUnitPeminjaman::create([
                        'id_detail_peminjaman' =>
                            $detail->id_detail_peminjaman,

                        'kode_aset' =>
                            $aset->kode_aset,

                        'status_unit' =>
                            'Dipinjam',
                    ]);

                    // Mengubah status aset menjadi Dipinjam.
                    $aset->update([
                        'status_aset' =>
                            'Dipinjam',
                    ]);
                }
            }
        }, 3);

        return redirect()
            ->route('peminjaman.index')
            ->with(
                'success',
                'Transaksi peminjaman berhasil disimpan.'
            );
    }


    /**
     * Menampilkan detail transaksi peminjaman.
     */
    public function show(string $id)
    {
        $peminjaman = PeminjamanAset::with([
            'detailPeminjaman.jenis',
            'detailPeminjaman.detailUnit.aset',
        ])->findOrFail($id);

        return view(
            'peminjaman.show',
            compact('peminjaman')
        );
    }


    /**
     * Membatalkan transaksi peminjaman.
     */
    public function batal(Request $request, string $id)
    {
        $validated = $request->validate([
            'catatan' => [
                'required',
                'string',
                'max:1000',
                'regex:/\S/',
            ],
        ], [
            'catatan.required' =>
                'Alasan pembatalan wajib diisi.',

            'catatan.regex' =>
                'Alasan pembatalan tidak boleh hanya berisi spasi.',

            'catatan.max' =>
                'Alasan pembatalan maksimal 1000 karakter.',
        ]);

        $alasan = trim($validated['catatan']);

        DB::transaction(function () use (
            $id,
            $alasan
        ) {

            $peminjaman = PeminjamanAset::where(
                'id_peminjaman',
                $id
            )
                ->lockForUpdate()
                ->firstOrFail();

            // Hanya transaksi yang masih Dipinjam
            // yang dapat dibatalkan.
            if (
                $peminjaman->status_peminjaman !== 'Dipinjam'
            ) {
                throw ValidationException::withMessages([
                    'catatan' =>
                        'Peminjaman ini tidak dapat dibatalkan.',
                ]);
            }

            $detailPeminjaman = DetailPeminjaman::where(
                'id_peminjaman',
                $peminjaman->id_peminjaman
            )->get();

            foreach ($detailPeminjaman as $detail) {

                $unitPeminjaman = DetailUnitPeminjaman::where(
                    'id_detail_peminjaman',
                    $detail->id_detail_peminjaman
                )
                    ->lockForUpdate()
                    ->get();

                foreach ($unitPeminjaman as $unit) {

                    // Mengunci dan memeriksa aset yang dipinjam.
                    $aset = Aset::where(
                        'kode_aset',
                        $unit->kode_aset
                    )
                        ->lockForUpdate()
                        ->first();

                    if (!$aset) {
                        throw ValidationException::withMessages([
                            'catatan' =>
                                'Data unit aset yang dipinjam tidak ditemukan.',
                        ]);
                    }

                    // Unit hanya dikembalikan ke Tersedia
                    // jika masih dalam kondisi Baik.
                    $statusAset = (
                        $aset->kondisi_aset === 'Baik'
                    )
                        ? 'Tersedia'
                        : 'Perlu Perbaikan';

                    $aset->update([
                        'status_aset' => $statusAset,
                    ]);

                    // Status detail unit mengikuti pembatalan.
                    // Nilai status_unit perlu disesuaikan
                    // dengan enum/aturan pada tabel detail unit.
                    $unit->update([
                        'status_unit' => 'Dibatalkan',
                    ]);
                }
            }

            // Mengubah status transaksi menjadi Dibatalkan.
            // Catatan awal saat ini digantikan alasan pembatalan.
            $peminjaman->update([
                'status_peminjaman' =>
                    'Dibatalkan',

                'catatan' =>
                    $alasan,
            ]);
        }, 3);

        return redirect()
            ->route('peminjaman.index')
            ->with(
                'success',
                'Transaksi peminjaman berhasil dibatalkan.'
            );
    }


    /**
     * Membuat kode peminjaman secara otomatis.
     */
    private function generateKodePeminjaman()
    {
        do {
            $kode = 'PMJ-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(4));

        } while (
            PeminjamanAset::where(
                'kode_peminjaman',
                $kode
            )->exists()
        );

        return $kode;
    }
}
