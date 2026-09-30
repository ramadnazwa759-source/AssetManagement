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

class PengembalianAsetController extends Controller
{
    // Menampilkan form pengembalian
    public function create(string $id)
    {
        $peminjaman = PeminjamanAset::with([
            'detailPeminjaman.jenis',
            'detailPeminjaman.detailUnit.aset'
        ])->findOrFail($id);

        // Transaksi yang sudah selesai tidak dapat dikembalikan lagi
        if (
            $peminjaman->status_peminjaman === 'Dikembalikan' ||
            $peminjaman->status_peminjaman === 'Dibatalkan'
        ) {
            return redirect()
                ->route('peminjaman.index')
                ->with(
                    'error',
                    'Transaksi ini sudah tidak dapat diproses.'
                );
        }

        // Mengambil unit yang belum dikembalikan
        $unit = $peminjaman
            ->detailPeminjaman
            ->flatMap(function ($detail) {
                return $detail->detailUnit
                    ->where('status_unit', 'Dipinjam');
            });

        return view(
            'asset-management.pengembalian.create',
            compact('peminjaman', 'unit')
        );
    }

      // Menyimpan pengembalian aset
    public function store(Request $request, string $id)
    {
        $validated = $request->validate([
            'tanggal_pengembalian' =>
                'required|date',

            'catatan' =>
                'nullable|string',

            'unit' =>
                'required|array|min:1',

            'unit.*' =>
                'required|in:Baik,Rusak',
        ], [
            'unit.required' =>
                'Minimal satu unit harus dipilih.',

            'unit.min' =>
                'Minimal satu unit harus dipilih.',

            'unit.*.in' =>
                'Kondisi unit harus Baik atau Rusak.',
        ]);

        DB::transaction(function () use (
            $validated,
            $id
        ) {
            $peminjaman = PeminjamanAset::findOrFail($id);

            // Memastikan transaksi masih dapat diproses
            if (
                $peminjaman->status_peminjaman === 'Dikembalikan' ||
                $peminjaman->status_peminjaman === 'Dibatalkan'
            ) {
                throw ValidationException::withMessages([
                    'unit' =>
                        'Transaksi ini sudah tidak dapat diproses.'
                ]);
            }

            $detailIds = DetailPeminjaman::where(
                'id_peminjaman',
                $peminjaman->id_peminjaman
            )->pluck('id_detail_peminjaman');

            $kodeAset = array_keys($validated['unit']);

            // Mengunci unit yang akan dikembalikan
            $detailUnit = DetailUnitPeminjaman::whereIn(
                'id_detail_peminjaman',
                $detailIds
            )
                ->whereIn(
                    'kode_aset',
                    $kodeAset
                )
                ->lockForUpdate()
                ->get();

            // Memastikan semua unit berasal dari transaksi ini
            if ($detailUnit->count() !== count($kodeAset)) {
                throw ValidationException::withMessages([
                    'unit' =>
                        'Terdapat unit aset yang tidak terdaftar pada transaksi ini.'
                ]);
            }

            // Memastikan unit belum pernah dikembalikan
            foreach ($detailUnit as $unit) {
                if ($unit->status_unit !== 'Dipinjam') {
                    throw ValidationException::withMessages([
                        'unit' =>
                            "Unit {$unit->kode_aset} sudah pernah dikembalikan."
                    ]);
                }
            }

            // Membuat catatan pengembalian
            $pengembalian = PengembalianAset::create([
                'id_peminjaman' =>
                    $peminjaman->id_peminjaman,

                'tanggal_pengembalian' =>
                    $validated['tanggal_pengembalian'],

                'jumlah_barang' =>
                    count($kodeAset),

                'catatan' =>
                    $validated['catatan'] ?? null,
            ]);

            // Memproses setiap unit yang dikembalikan
            foreach ($detailUnit as $unit) {

                $kondisiKembali =
                    $validated['unit'][$unit->kode_aset];

                // Menyimpan riwayat detail pengembalian
                DetailPengembalian::create([
                    'id_pengembalian' =>
                        $pengembalian->id_pengembalian,

                    'kode_aset' =>
                        $unit->kode_aset,

                    'kondisi_kembali' =>
                        $kondisiKembali,
                ]);

                // Menentukan kondisi dan status terkini aset
                if ($kondisiKembali === 'Baik') {

                    $kondisiAset = 'Baik';
                    $statusAset = 'Tersedia';

                } else {

                    $kondisiAset = 'Rusak Ringan';
                    $statusAset = 'Perlu Perbaikan';
                }

                // Mengubah kondisi dan status aset
                Aset::where(
                    'kode_aset',
                    $unit->kode_aset
                )->update([
                    'kondisi_aset' => $kondisiAset,
                    'status_aset' => $statusAset,
                ]);

                // Menandai unit sudah dikembalikan
                $unit->update([
                    'status_unit' =>
                        'Dikembalikan',

                    'tanggal_dikembalikan' =>
                        $validated['tanggal_pengembalian'],
                ]);
            }

            // Menghitung total unit dalam transaksi
            $totalUnit = DetailUnitPeminjaman::whereIn(
                'id_detail_peminjaman',
                $detailIds
            )->count();

            // Menghitung total unit yang sudah dikembalikan
            $totalDikembalikan = DetailUnitPeminjaman::whereIn(
                'id_detail_peminjaman',
                $detailIds
            )
                ->where(
                    'status_unit',
                    'Dikembalikan'
                )
                ->count();

            // Menentukan status transaksi
            if ($totalDikembalikan === 0) {

                $statusPeminjaman = 'Dipinjam';

            } elseif (
                $totalDikembalikan < $totalUnit
            ) {

                $statusPeminjaman =
                    'Sebagian Dikembalikan';

            } else {

                $statusPeminjaman =
                    'Dikembalikan';
            }

            // Memperbarui transaksi peminjaman
            $peminjaman->update([
                'jumlah_dikembalikan' =>
                    $totalDikembalikan,

                'status_peminjaman' =>
                    $statusPeminjaman,
            ]);
        });

        return redirect()
            ->route('peminjaman.show', $id)
            ->with(
                'success',
                'Pengembalian aset berhasil disimpan.'
            );
    }


}
