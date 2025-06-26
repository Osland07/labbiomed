<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auto_validates', function (Blueprint $table) {
            $table->id();
            $table->boolean('peminjaman')->default(false);
            $table->boolean('penggunaan')->default(false);
            $table->boolean('pengembalian')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_validates');
    }
};
