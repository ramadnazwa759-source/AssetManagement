<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $table = 'aset';

    protected $primaryKey = 'kode_aset';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'kode_aset',
        'id_jenis',
        'id_lokasi',
        'nama_aset',
        'tanggal_beli',
        'kondisi_aset',
        'status_aset',
        'gambar',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
    ];

    // Relasi ke Jenis Aset
    public function jenis()
    {
        return $this->belongsTo(
            JenisAset::class,
            'id_jenis',
            'id_jenis'
        );
    }
}
