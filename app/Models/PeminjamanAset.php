<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanAset extends Model
{
    protected $table = 'peminjaman_aset';
    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'id_jenis',
        'nama_peminjam',
        'tanggal_pinjam',
        'jumlah_pinjam',
        'tujuan',
        'tanggal_pengembalian',
        'jumlah_dikembalikan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_pengembalian' => 'date',
    ];

    public function jenis()
    {
        return $this->belongsTo(
            JenisAset::class,
            'id_jenis',
            'id_jenis'
        );
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(
            DetailPeminjaman::class,
            'id_peminjaman',
            'id_peminjaman'
        );
    }

    public function pengembalian()
    {
        return $this->hasMany(
            PengembalianAset::class,
            'id_peminjaman',
            'id_peminjaman'
        );
    }
}
