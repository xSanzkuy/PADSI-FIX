<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaiTable extends Migration
{
    public function up()
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id(); // id primary key
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('alamat');
            $table->unsignedBigInteger('id_role');
            $table->unsignedBigInteger('user_id')->nullable(); // Tidak perlu after('id'), cukup menambah kolom user_id
            $table->foreign('id_role')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null'); // Menambahkan foreign key untuk user_id
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pegawai');
    }
}
