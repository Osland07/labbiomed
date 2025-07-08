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
        // Ensure jumlah column in bahan_logs table is decimal
        Schema::table('bahan_logs', function (Blueprint $table) {
            $table->decimal('jumlah', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert jumlah column in bahan_logs table back to integer
        Schema::table('bahan_logs', function (Blueprint $table) {
            $table->integer('jumlah')->change();
        });
    }
}; 