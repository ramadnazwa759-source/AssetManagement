<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(
            JenisAset::class,
            'id_jenis',
            'id_jenis'
        );
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(
            LokasiAset::class,
            'id_lokasi',
            'id_lokasi'
        );
    }
}
