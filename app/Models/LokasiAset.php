<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiAset extends Model
{
    protected $table = 'lokasi_aset';

    protected $primaryKey = 'id_lokasi';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_lokasi',
        'nama_lokasi',
        'deskripsi',
    ];
}