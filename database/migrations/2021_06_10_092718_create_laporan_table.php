<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('dosen_id')->nullable();
            $table->foreignId('alat_id')->nullable();
            $table->foreignId('bahan_id')->nullable();
            $table->foreignId('ruangan_id')->nullable();
            $table->string('tujuan_penggunaan')->nullable();
            $table->string('catatan')->nullable();
            $table->dateTime('tgl_kerusakan')->nullable();
            $table->dateTime('tgl_pengembalian')->nullable();
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->string('status_peminjaman')->nullable();
            $table->string('status_penggunaan')->nullable();
            $table->string('status_pengembalian')->nullable();
            $table->string('kondisi_sebelum')->nullable()->default('Baik');
            $table->string('kondisi_setelah')->nullable();
            $table->string('deskripsi_kerusakan')->nullable();
            $table->string('gambar_sebelum')->nullable();
            $table->string('gambar_setelah')->nullable();
            $table->string('surat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laporans');
    }
};
