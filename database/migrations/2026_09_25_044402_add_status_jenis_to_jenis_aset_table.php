<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_aset', function (Blueprint $table) {
            $table->enum('status_jenis', ['Aktif', 'Nonaktif'])
                ->default('Aktif')
                ->after('stok');
        });
    }

    public function down(): void
    {
        Schema::table('jenis_aset', function (Blueprint $table) {
            $table->dropColumn('status_jenis');
        });
    }
};
