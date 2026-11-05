<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';

    protected $primaryKey = 'id_detail_peminjaman';

    // ID menggunakan AUTO_INCREMENT BIGINT
    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_peminjaman',
        'id_jenis',
        'jumlah_pinjam',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(
            PeminjamanAset::class,
            'id_peminjaman',
            'id_peminjaman'
        );
    }

    public function jenis()
    {
        return $this->belongsTo(
            JenisAset::class,
            'id_jenis',
            'id_jenis'
        );
    }

    public function detailUnit()
    {
        return $this->hasMany(
            DetailUnitPeminjaman::class,
            'id_detail_peminjaman',
            'id_detail_peminjaman'
        );
    }
}
