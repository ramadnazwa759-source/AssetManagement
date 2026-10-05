<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanAset extends Model
{
    protected $table = 'peminjaman_aset';

    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'nama_peminjam',
        'tanggal_pinjam',
        'tujuan',
        'tanggal_pengembalian',
        'jumlah_dikembalikan',
        'status_peminjaman',
        'catatan',
    ];

    public function detailPeminjaman()
    {
        return $this->hasMany(
            DetailPeminjaman::class,
            'id_peminjaman',
            'id_peminjaman'
        );
    }
}
