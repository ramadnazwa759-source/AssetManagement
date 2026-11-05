<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Menyelaraskan tabel `aset` hasil migration dengan database asli.
 *
 * Temuan (SHOW COLUMNS):
 * - id_lokasi : database asli NULL (YES), hasil migration NOT NULL (NO)
 * - nama_aset : database asli varchar(100), hasil migration varchar(20)
 *
 * Alasan teknis:
 * - addStock() dan store() membuat unit baru dengan id_lokasi = null
 *   (lokasi diisi admin belakangan melalui AsetController::update()).
 * - nama_aset menyalin nama_jenis, yang boleh sampai 100 karakter.
 *
 * Pada database asli perintah ini tidak mengubah apa pun karena
 * definisinya sudah sama.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE aset MODIFY id_lokasi VARCHAR(25) NULL');
        DB::statement('ALTER TABLE aset MODIFY nama_aset VARCHAR(100) NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE aset MODIFY nama_aset VARCHAR(20) NOT NULL');
        DB::statement('ALTER TABLE aset MODIFY id_lokasi VARCHAR(25) NOT NULL');
    }
};