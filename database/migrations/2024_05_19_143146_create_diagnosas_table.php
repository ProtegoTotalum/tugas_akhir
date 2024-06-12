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
        Schema::create('diagnosas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('id_penyakit')->nullable();
            $table->foreign('id_penyakit')->references('id')->on('penyakits')->onDelete('cascade');
            $table->unsignedBigInteger('id_dokter')->nullable();
            $table->foreign('id_dokter')->references('id')->on('users')->onDelete('cascade');
            $table->string('nomor_diagnosa_user');
            $table->string('nomor_diagnosa');
            $table->double('persentase_hasil',8,2)->nullable();
            $table->date('tanggal_diagnosa')->nullable();
            $table->time('jam_diagnosa')->nullable();
            $table->integer('konfirmasi_dokter')->nullable();
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
        Schema::dropIfExists('diagnosas');
    }
};
