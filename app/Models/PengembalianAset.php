<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengembalianAset extends Model
{
    protected $table = 'pengembalian_aset';

    protected $primaryKey = 'id_pengembalian';

    protected $fillable = [
        'id_peminjaman',
        'tanggal_pengembalian',
        'jumlah_barang',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pengembalian' => 'date',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(
            PeminjamanAset::class,
            'id_peminjaman',
            'id_peminjaman'
        );
    }

    public function detailPengembalian()
    {
        return $this->hasMany(
            DetailPengembalian::class,
            'id_pengembalian',
            'id_pengembalian'
        );
    }
}
