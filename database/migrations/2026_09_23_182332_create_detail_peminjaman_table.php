<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->string('id_detail_peminjaman', 25)->primary();
            $table->unsignedBigInteger('id_peminjaman');
            $table->string('id_aset', 25);
            $table->timestamps();

            $table->foreign('id_peminjaman')
                ->references('id_peminjaman')
                ->on('peminjaman_aset')
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
        Schema::dropIfExists('detail_peminjaman');
    }
};
