<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset', function (Blueprint $table) {
            $table->string('kode_aset', 25)->primary();
            $table->string('id_jenis', 25);
            $table->string('id_lokasi', 25);
            $table->string('nama_aset', 20);
            $table->date('tanggal_beli')->nullable();
            $table->enum('kondisi_aset', [
                'Baik',
                'Rusak Ringan',
                'Rusak Berat'
            ])->default('Baik');
            $table->string('status_aset', 20)->default('Tersedia');
            $table->string('gambar', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_jenis')
                ->references('id_jenis')
                ->on('jenis_aset')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('id_lokasi')
                ->references('id_lokasi')
                ->on('lokasi_aset')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aset');
    }
};
