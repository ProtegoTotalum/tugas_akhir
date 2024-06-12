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
        Schema::create('bahan_makanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan_makanan');
            $table->string('takaran(g)')->nullable();
            $table->float('kalori')->nullable();
            $table->float('karbohidrat')->nullable();
            $table->float('protein_nabati')->nullable();
            $table->float('protein_hewani')->nullable();
            $table->float('lemak')->nullable();       
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
        Schema::dropIfExists('bahan_makanans');
    }
};
