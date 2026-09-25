<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->string('id_detail_pengembalian', 25)->primary();

            $table->unsignedBigInteger('id_pengembalian');
            $table->string('id_aset', 25);

            $table->enum('kondisi', [
                'Baik',
                'Rusak Ringan',
                'Rusak Berat'
            ]);

            $table->enum('status', [
                'Dikembalikan',
                'Hilang'
            ]);

            $table->timestamps();

            $table->foreign('id_pengembalian')
                ->references('id_pengembalian')
                ->on('pengembalian_aset')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_aset')
                ->references('kode_aset')
                ->on('aset')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');
    }
};
