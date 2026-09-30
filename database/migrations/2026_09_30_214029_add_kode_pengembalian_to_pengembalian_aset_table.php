<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Menambahkan kode pengembalian
    public function up(): void
    {
        Schema::table('pengembalian_aset', function (Blueprint $table) {
            $table->string('kode_pengembalian', 25)
                ->unique()
                ->after('id_pengembalian');
        });
    }

    // Menghapus kode pengembalian
    public function down(): void
    {
        Schema::table('pengembalian_aset', function (Blueprint $table) {
            $table->dropUnique([
                'kode_pengembalian'
            ]);

            $table->dropColumn('kode_pengembalian');
        });
    }
};
