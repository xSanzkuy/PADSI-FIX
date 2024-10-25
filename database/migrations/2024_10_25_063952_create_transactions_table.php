<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');
        $table->unsignedBigInteger('pegawai_id');
        $table->string('telp_pelanggan')->nullable();
        $table->decimal('total_bayar', 15, 2);
        $table->decimal('nominal', 15, 2);
        $table->decimal('kembalian', 15, 2);
        $table->timestamps();

        // Foreign key constraint
        $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
    });
}
};
