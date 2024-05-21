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
        Schema::create('certainty_factors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penyakit')->nullable();
            $table->foreign('id_penyakit')->references('id')->on('penyakits')->onDelete('cascade');
            $table->unsignedBigInteger('id_gejala')->nullable();
            $table->foreign('id_gejala')->references('id')->on('gejalas')->onDelete('cascade');
            $table->float('certainty_factor');
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
        Schema::dropIfExists('certainty_factors');
    }
};
