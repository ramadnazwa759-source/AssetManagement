<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Mengubah status aset menjadi enum khusus MySQL
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

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
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("
            ALTER TABLE aset
            MODIFY status_aset
            VARCHAR(20)
            NOT NULL DEFAULT 'Tersedia'
        ");
    }
};
