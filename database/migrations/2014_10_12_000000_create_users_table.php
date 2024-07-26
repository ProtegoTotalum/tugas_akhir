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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('nama_user');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('tgl_lahir_user');
            $table->string('umur_user')->nullable();
            $table->string('bb_user')->nullable();
            $table->string('tinggi_user')->nullable();
            $table->string('no_telp_user');
            $table->string('gender_user');
            $table->string('alamat_user');
            $table->string('kota_user');
            $table->string('provinsi_user');
            $table->string('role_user')->nullable();
            $table->integer('deaktivasi')->nullable();
            $table->string('fcm_token')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
