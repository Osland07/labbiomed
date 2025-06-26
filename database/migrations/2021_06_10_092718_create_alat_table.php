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
        Schema::create('alats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serial_number')->nullable();
            $table->boolean('auto_validate')->default(false);
            $table->text('desc')->nullable();
            $table->string('img')->nullable();
            $table->string('condition')->nullable()->default('Baik');
            $table->string('status')->nullable()->default('Tersedia');
            $table->foreignId('location')->nullable();
            $table->string('detail_location')->nullable();
            $table->string('date_received')->nullable();
            $table->string('source')->nullable();
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
        Schema::dropIfExists('alats');
    }
};
