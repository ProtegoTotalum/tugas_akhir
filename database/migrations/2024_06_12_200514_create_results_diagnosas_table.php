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
        Schema::create('results_diagnosas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_diagnosa')->nullable();
            $table->foreign('id_diagnosa')->references('id')->on('diagnosas')->onDelete('cascade');
            $table->unsignedBigInteger('id_penyakit')->nullable();
            $table->foreign('id_penyakit')->references('id')->on('penyakits')->onDelete('cascade');
            $table->double('hasil_cf_komb',8,4)->nullable();
            $table->double('hasil_cf_komb_persen',8,2)->nullable();
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
        Schema::dropIfExists('results_diagnosas');
    }
};
