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
            $table->id('id');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->string('tipe_jadwal_makan');
            $table->time('waktu_makan');
            $table->integer('senin');
            $table->integer('selasa');
            $table->integer('rabu');
            $table->integer('kamis');
            $table->integer('jumat');
            $table->integer('sabtu');
            $table->integer('minggu');
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
