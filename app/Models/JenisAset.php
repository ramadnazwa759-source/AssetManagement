<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisAset extends Model
{
    protected $table = 'jenis_aset';
    protected $primaryKey = 'id_jenis';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_jenis',
        'id_sub_kategori_aset',
        'nama_jenis',
        'stok',
        'deskripsi',
    ];

    public function aset()
    {
        return $this->hasMany(
            Aset::class,
            'id_jenis',
            'id_jenis'
        );
    }

    public function subKategori()
    {
        return $this->belongsTo(
            SubKategoriAset::class,
            'id_sub_kategori_aset',
            'id_sub_kategori'
        );
    }
}
