<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';

    protected $primaryKey = 'id_detail_peminjaman';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_detail_peminjaman',
        'id_peminjaman',
        'id_aset',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(
            PeminjamanAset::class,
            'id_peminjaman',
            'id_peminjaman'
        );
    }

    public function aset()
    {
        return $this->belongsTo(
            Aset::class,
            'id_aset',
            'kode_aset'
        );
    }
}
