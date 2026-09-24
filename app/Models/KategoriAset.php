<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriAset extends Model
{
    protected $table = 'kategori_aset';

    protected $primaryKey = 'id_kategori';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_kategori',
        'nama_kategori',
        'Deskripsi',
    ];

    const CREATED_AT = 'Created_at';
    const UPDATED_AT = 'Update_at';
}