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
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serial_number')->nullable();
            $table->boolean('auto_validate')->default(false);
            $table->string('kapasitas')->nullable();
            $table->string('gedung')->nullable();
            $table->string('lantai')->nullable();
            $table->string('status')->nullable()->default('Tersedia');
            $table->string('foto_ruangan')->nullable();
            $table->string('foto_denah')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('category_id')->nullable();
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
        Schema::dropIfExists('ruangans');
    }
};
