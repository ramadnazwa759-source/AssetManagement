<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\DetailPeminjaman;
use App\Models\DetailUnitPeminjaman;
use App\Models\PengembalianAset;
use App\Models\DetailPengembalian;
use App\Models\PeminjamanAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PengembalianAsetController extends Controller
{
    /**
     * Menampilkan form pengembalian aset.
     */
    public function create(string $id)
    {
        // Memastikan transaksi peminjaman terdaftar.
        $peminjaman = PeminjamanAset::with([
            'detailPeminjaman.jenis',
            'detailPeminjaman.detailUnit.aset'
        ])->findOrFail($id);

        // Pengembalian hanya dapat dilakukan pada transaksi
        // yang masih memiliki unit berstatus Dipinjam.
        if (!in_array($peminjaman->status_peminjaman, [
            'Dipinjam',
            'Sebagian Dikembalikan'
        ])) {
            return redirect()
                ->route('peminjaman.index')
                ->with(
                    'error',
                    'Transaksi ini sudah tidak dapat diproses untuk pengembalian.'
                );
        }

        // Mengambil unit yang belum dikembalikan.
        $unit = $peminjaman->detailPeminjaman
            ->flatMap(function ($detail) {
                return $detail->detailUnit
                    ->where('status_unit', 'Dipinjam');
            })
            ->values();

        // Memastikan masih ada unit yang dapat dikembalikan.
        if ($unit->isEmpty()) {
            return redirect()
                ->route('peminjaman.show', $id)
                ->with(
                    'error',
                    'Tidak ada unit aset yang belum dikembalikan.'
                );
        }

        return view(
            'asset-management.pengembalian.create',
            compact('peminjaman', 'unit')
        );
    }

    /**
     * Menyimpan pengembalian aset.
     */
    public function store(Request $request, string $id)
    {
        // Memastikan transaksi peminjaman terdaftar.
        $peminjamanAwal = PeminjamanAset::findOrFail($id);

        // Validasi tanggal pengembalian.
        $validated = $request->validate([
            'tanggal_pengembalian' => [
                'required',
                'date',
                'after_or_equal:' . $peminjamanAwal->tanggal_pinjam,
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'unit' => [
                'required',
                'array',
                'min:1',
            ],

            'unit.*' => [
                'required',
                'in:Baik,Rusak',
            ],
        ], [
            'tanggal_pengembalian.required' =>
                'Tanggal pengembalian wajib diisi.',

            'tanggal_pengembalian.date' =>
                'Tanggal pengembalian harus berupa tanggal yang valid.',

            'tanggal_pengembalian.after_or_equal' =>
                'Tanggal pengembalian tidak boleh lebih awal dari tanggal peminjaman.',

            'unit.required' =>
                'Minimal satu unit aset harus dipilih.',

            'unit.array' =>
                'Data unit aset tidak valid.',

            'unit.min' =>
                'Minimal satu unit aset harus dipilih.',

            'unit.*.required' =>
                'Kondisi setiap unit aset wajib ditentukan.',

            'unit.*.in' =>
                'Kondisi unit aset hanya dapat dipilih Baik atau Rusak.',

            'catatan.max' =>
                'Catatan maksimal 1000 karakter.',
        ]);

        DB::transaction(function () use ($validated, $id) {

            // Mengunci transaksi agar tidak diproses bersamaan.
            $peminjaman = PeminjamanAset::where(
                'id_peminjaman',
                $id
            )
                ->lockForUpdate()
                ->firstOrFail();

            // Memastikan transaksi masih dapat dikembalikan.
            if (!in_array($peminjaman->status_peminjaman, [
                'Dipinjam',
                'Sebagian Dikembalikan'
            ])) {
                throw ValidationException::withMessages([
                    'unit' =>
                        'Transaksi ini sudah tidak dapat diproses untuk pengembalian.',
                ]);
            }

            // Memastikan tanggal tidak lebih awal dari tanggal peminjaman.
            if (
                $validated['tanggal_pengembalian'] <
                $peminjaman->tanggal_pinjam
            ) {
                throw ValidationException::withMessages([
                    'tanggal_pengembalian' =>
                        'Tanggal pengembalian tidak boleh lebih awal dari tanggal peminjaman.',
                ]);
            }

            $kodeAset = array_keys($validated['unit']);

            // Mengambil detail peminjaman milik transaksi yang dipilih.
            $detailPeminjaman = DetailPeminjaman::where(
                'id_peminjaman',
                $peminjaman->id_peminjaman
            )
                ->lockForUpdate()
                ->get();

            $detailIds = $detailPeminjaman->pluck(
                'id_detail_peminjaman'
            );

            // Mengunci unit yang akan dikembalikan.
            $detailUnit = DetailUnitPeminjaman::whereIn(
                'id_detail_peminjaman',
                $detailIds
            )
                ->whereIn('kode_aset', $kodeAset)
                ->lockForUpdate()
                ->get();

            // Memastikan seluruh unit berasal dari transaksi ini.
            if ($detailUnit->count() !== count($kodeAset)) {
                throw ValidationException::withMessages([
                    'unit' =>
                        'Terdapat unit aset yang tidak terdaftar pada transaksi peminjaman ini.',
                ]);
            }

            // Memastikan tidak ada unit yang diproses lebih dari sekali.
            foreach ($detailUnit as $unit) {
                if ($unit->status_unit !== 'Dipinjam') {
                    throw ValidationException::withMessages([
                        'unit' =>
                            "Unit {$unit->kode_aset} sudah dikembalikan atau tidak dapat diproses kembali.",
                    ]);
                }
            }

            // Mengunci data aset yang akan dikembalikan.
            $aset = Aset::whereIn('kode_aset', $kodeAset)
                ->lockForUpdate()
                ->get()
                ->keyBy('kode_aset');

            // Memastikan seluruh aset masih terdaftar dan berstatus Dipinjam.
            foreach ($detailUnit as $unit) {
                $dataAset = $aset->get($unit->kode_aset);

                if (!$dataAset || $dataAset->status_aset !== 'Dipinjam') {
                    throw ValidationException::withMessages([
                        'unit' =>
                            "Data aset {$unit->kode_aset} tidak valid atau statusnya bukan Dipinjam.",
                    ]);
                }
            }

            // Membuat data transaksi pengembalian.
            $pengembalian = PengembalianAset::create([
                'kode_pengembalian' =>
                    $this->generateKodePengembalian(),

                'id_peminjaman' =>
                    $peminjaman->id_peminjaman,

                'tanggal_pengembalian' =>
                    $validated['tanggal_pengembalian'],

                'jumlah_barang' =>
                    count($kodeAset),

                'catatan' =>
                    $validated['catatan'] ?? null,
            ]);

            // Memproses setiap unit yang dikembalikan.
            foreach ($detailUnit as $unit) {

                $kondisiKembali = $validated['unit'][$unit->kode_aset];

                // Menyimpan detail pengembalian setiap unit.
                DetailPengembalian::create([
                    'id_pengembalian' =>
                        $pengembalian->id_pengembalian,

                    'kode_aset' =>
                        $unit->kode_aset,

                    'kondisi_kembali' =>
                        $kondisiKembali,
                ]);

                // Menentukan kondisi dan status aset.
                if ($kondisiKembali === 'Baik') {
                    $kondisiAset = 'Baik';
                    $statusAset = 'Tersedia';
                } else {
                    $kondisiAset = 'Rusak Ringan';
                    $statusAset = 'Perlu Perbaikan';
                }

                // Memperbarui kondisi dan status aset.
                Aset::where('kode_aset', $unit->kode_aset)
                    ->update([
                        'kondisi_aset' => $kondisiAset,
                        'status_aset' => $statusAset,
                    ]);

                // Menandai unit peminjaman sudah dikembalikan.
                $unit->update([
                    'status_unit' => 'Dikembalikan',
                    'tanggal_dikembalikan' =>
                        $validated['tanggal_pengembalian'],
                ]);
            }

            // Menghitung jumlah unit yang sudah dikembalikan
            // pada masing-masing detail peminjaman.
            foreach ($detailPeminjaman as $detail) {
                $jumlahDikembalikan = DetailUnitPeminjaman::where(
                    'id_detail_peminjaman',
                    $detail->id_detail_peminjaman
                )
                    ->where('status_unit', 'Dikembalikan')
                    ->count();

                $detail->update([
                    'jumlah_dikembalikan' => $jumlahDikembalikan,
                ]);
            }

            // Menghitung seluruh unit dalam transaksi.
            $totalUnit = DetailUnitPeminjaman::whereIn(
                'id_detail_peminjaman',
                $detailIds
            )->count();

            // Menghitung seluruh unit yang sudah dikembalikan.
            $totalDikembalikan = DetailUnitPeminjaman::whereIn(
                'id_detail_peminjaman',
                $detailIds
            )
                ->where('status_unit', 'Dikembalikan')
                ->count();

            // Menentukan status transaksi berdasarkan kondisi seluruh unit.
            if ($totalDikembalikan === $totalUnit) {
                $statusPeminjaman = 'Dikembalikan';
            } elseif ($totalDikembalikan > 0) {
                $statusPeminjaman = 'Sebagian Dikembalikan';
            } else {
                $statusPeminjaman = 'Dipinjam';
            }

            // Memperbarui jumlah dan status transaksi peminjaman.
            $peminjaman->update([
                'jumlah_dikembalikan' => $totalDikembalikan,
                'status_peminjaman' => $statusPeminjaman,
            ]);

            /*
             * PENCATATAN RIWAYAT ASET
             *
             * Bagian ini perlu ditambahkan setelah struktur tabel
             * dan model RiwayatAset diketahui.
             */
        });

        return redirect()
            ->route('peminjaman.show', $id)
            ->with(
                'success',
                'Pengembalian aset berhasil disimpan.'
            );
    }

    /**
     * Membuat kode pengembalian otomatis.
     */
    private function generateKodePengembalian()
    {
        do {
            $kode = 'PGM-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(4));

        } while (
            PengembalianAset::where(
                'kode_pengembalian',
                $kode
            )->exists()
        );

        return $kode;
    }
}
