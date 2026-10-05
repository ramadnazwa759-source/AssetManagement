<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Mengubah status aset menjadi enum
    public function up(): void
    {
        DB::statement("
            ALTER TABLE aset
            MODIFY status_aset
            ENUM('Tersedia', 'Dipinjam', 'Nonaktif')
            NOT NULL DEFAULT 'Tersedia'
        ");
    }

    // Mengembalikan status aset menjadi varchar
    public function down(): void
    {
        DB::statement("
            ALTER TABLE aset
            MODIFY status_aset
            VARCHAR(20)
            NOT NULL DEFAULT 'Tersedia'
        ");
    }
};
