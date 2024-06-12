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
        Schema::create('jadwal_makans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->integer('status_jadwal');
            $table->string('tipe_jadwal_makan');
            $table->string('pengulangan_jadwal_makan');
            $table->time('waktu_makan');
            $table->integer('senin')->nullable();
            $table->integer('selasa')->nullable();
            $table->integer('rabu')->nullable();
            $table->integer('kamis')->nullable();
            $table->integer('jumat')->nullable();
            $table->integer('sabtu')->nullable();
            $table->integer('minggu')->nullable();
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
        Schema::dropIfExists('jadwal_makans');
    }
};
