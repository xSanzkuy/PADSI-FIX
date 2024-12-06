<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id')->primary(); // Atau ganti dengan $table->id() atau $table->uuid('id')
            $table->date('tanggal');
            $table->unsignedBigInteger('pegawai_id');
            $table->string('telp_pelanggan')->nullable();
            $table->decimal('total_bayar', 15, 2);
            $table->decimal('nominal', 15, 2);
            $table->decimal('kembalian', 15, 2);
            $table->timestamps();
            $table->unsignedBigInteger('member_id')->nullable()->after('pegawai_id');
    
            $table->foreign('member_id')->references('id')->on('members')->onDelete('set null');
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
