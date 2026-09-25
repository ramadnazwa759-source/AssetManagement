<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_kategori_aset', function (Blueprint $table) {
            $table->string('id_sub_kategori', 25)->primary();
            $table->string('id_kategori', 25);
            $table->string('nama_sub_kategori', 100);
            $table->string('gambar', 225)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategori_aset')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kategori_aset');
    }
};
