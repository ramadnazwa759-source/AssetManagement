<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Menambahkan status perlu perbaikan pada aset
    public function up(): void
    {
        DB::statement("
            ALTER TABLE aset
            MODIFY status_aset
            ENUM(
                'Tersedia',
                'Dipinjam',
                'Perlu Perbaikan',
                'Nonaktif'
            )
            NOT NULL DEFAULT 'Tersedia'
        ");
    }

    // Mengembalikan status aset
    public function down(): void
    {
        DB::statement("
            ALTER TABLE aset
            MODIFY status_aset
            ENUM(
                'Tersedia',
                'Dipinjam',
                'Nonaktif'
            )
            NOT NULL DEFAULT 'Tersedia'
        ");
    }
};
