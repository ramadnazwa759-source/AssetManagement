<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KategoriAset;
use App\Models\JenisAset;

class SubKategoriAset extends Model
{
    protected $table = 'sub_kategori_aset';

    protected $primaryKey = 'id_sub_kategori';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_sub_kategori',
        'id_kategori',
        'nama_sub_kategori',
        'gambar',
        'deskripsi',
    ];

    public function kategori()
    {
        return $this->belongsTo(
            KategoriAset::class,
            'id_kategori',
            'id_kategori'
        );
    }

    public function jenisAset()
    {
        return $this->hasMany(
            JenisAset::class,
            'id_sub_kategori_aset',
            'id_sub_kategori'
        );
    }
}