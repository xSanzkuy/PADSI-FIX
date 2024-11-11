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
            $table->string('id')->primary(); // Mengubah id menjadi string dan menjadikannya primary key
            $table->date('tanggal');
            $table->unsignedBigInteger('pegawai_id');
            $table->string('telp_pelanggan')->nullable();
            $table->decimal('total_bayar', 15, 2);
            $table->decimal('nominal', 15, 2);
            $table->decimal('kembalian', 15, 2);
            $table->timestamps();
            $table->unsignedBigInteger('member_id')->nullable()->after('pegawai_id');
    
            // Jika member dihapus, transaksi tetap ada
            $table->foreign('member_id')->references('id')->on('members')->onDelete('set null');

            // Foreign key constraint
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade');
        });
    }
};
 