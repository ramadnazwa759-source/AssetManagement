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
        'protected $fillable = [
        'id_detail_peminjaman',
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
}
