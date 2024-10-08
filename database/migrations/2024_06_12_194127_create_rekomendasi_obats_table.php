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
        Schema::create('rekomendasi_obats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_obat')->nullable();
            $table->foreign('id_obat')->references('id')->on('obats')->onDelete('cascade');
            $table->unsignedBigInteger('id_analisa')->nullable();
            $table->foreign('id_analisa')->references('id')->on('analisa_dokters')->onDelete('cascade');
            $table->string('aturan_minum_obat')->nullable();
            $table->string('catatan_minum_obat')->nullable();
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
        Schema::dropIfExists('rekomendasi_obats');
    }
};
