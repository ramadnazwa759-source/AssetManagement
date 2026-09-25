<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengembalian extends Model
{
    protected $table = 'detail_pengembalian';
    protected $primaryKey = 'id_detail_pengembalian';
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_detail_pengembalian',
        'id_pengembalian',
        'id_aset',
        'kondisi',
        'status',
    ];

    public function pengembalian()
    {
        return $this->belongsTo(
            PengembalianAset::class,
            'id_pengembalian',
            'id_pengembalian'
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
