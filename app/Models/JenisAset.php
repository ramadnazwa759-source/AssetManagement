<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    protected $table = 'aset';

    protected $primaryKey = 'kode_aset';

    protected $table = 'jenis_aset';

    protected $primaryKey = 'id_jenis';

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
        'id_sub_kategori_aset',
        'nama_jenis',
        'stok',
        'status_jenis',
        'deskripsi',
    ];

    // Relasi ke jenis aset
    public function jenis()
    {
        return $this->belongsTo(
            JenisAset::class,
            'id_jenis',
            'id_jenis'
        );
    }

    // Relasi ke lokasi aset
    public function lokasi()
    {
        return $this->belongsTo(
            LokasiAset::class,
            'id_lokasi',
            'id_lokasi'
        );
    }
}