<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaiTable extends Migration
{
    public function up()
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('alamat');
            $table->unsignedBigInteger('id_role');
            $table->unsignedBigInteger('user_id')->nullable()->after('id'); // Tambahkan kolom user_id
            $table->foreign('id_role')->references('id')->on('roles')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }
    }