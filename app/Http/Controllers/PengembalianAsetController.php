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

  
}
