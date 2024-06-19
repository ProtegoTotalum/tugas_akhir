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
        Schema::create('analisa_dokters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_diagnosa')->nullable();
            $table->foreign('id_diagnosa')->references('id')->on('diagnosas')->onDelete('cascade');
            $table->unsignedBigInteger('id_dokter')->nullable();
            $table->foreign('id_dokter')->references('id')->on('users')->onDelete('cascade');
            $table->string('analisa_dokter')->nullable();
            $table->date('tanggal_analisa')->nullable();
            $table->time('jam_analisa')->nullable();
            $table->integer('reminder_analisa')->nullable();
            $table->integer('status_analisa')->nullable();
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
        Schema::dropIfExists('analisa_dokters');
    }
};
