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
        Schema::create('larangan_bahan_makanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bahan_makanan')->nullable();
            $table->foreign('id_bahan_makanan')->references('id')->on('bahan_makanans')->onDelete('cascade');
            $table->unsignedBigInteger('id_penyakit')->nullable();
            $table->foreign('id_penyakit')->references('id')->on('penyakits')->onDelete('cascade');
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
        Schema::dropIfExists('larangan_bahan_makanans');
    }
};
