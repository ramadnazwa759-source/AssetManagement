<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailUnitPeminjaman extends Model
{
    protected $table = 'detail_unit_peminjaman';

    protected $primaryKey = 'id_detail_unit_peminjaman';

    protected $fillable = [
        'id_detail_peminjaman',
        'kode_aset',
        'status_unit',
        'tanggal_dikembalikan',
    ];

    protected $casts = [
        'tanggal_dikembalikan' => 'date',
    ];

    // Relasi ke detail peminjaman
    public function detailPeminjaman(): BelongsTo
    {
        return $this->belongsTo(
            DetailPeminjaman::class,
            'id_detail_peminjaman',
            'id_detail_peminjaman'
        );
    }

    // Relasi ke aset
    public function aset(): BelongsTo
    {
        return $this->belongsTo(
            Aset::class,
            'kode_aset',
            'kode_aset'
        );
    }
}
