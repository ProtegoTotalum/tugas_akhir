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
        Schema::create('penyakits', function (Blueprint $table) {
            $table->id('id');
            $table->string('nama_penyakit');
            $table->string('deskripsi_penyakit');
            $table->string('gejala_penyakit');
            $table->string('penyebab_penyakit');
            $table->string('penyebaran_penyakit');
            $table->string('cara_pencegahan');
            $table->string('cara_penanganan');
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
        Schema::dropIfExists('penyakits');
    }
};
