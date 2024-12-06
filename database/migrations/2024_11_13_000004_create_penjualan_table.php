<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->string('no_penjualan', 20)->unique(); // Penjualan ID, kolom yang unik
            $table->string('telp_pelanggan', 20)->nullable(); // Nomor telepon pelanggan, opsional
            $table->unsignedInteger('total_harga'); // Total harga penjualan
            $table->unsignedInteger('bayar'); // Jumlah yang dibayarkan pelanggan
            $table->string('no_pegawai', 20); // Nomor pegawai yang menangani
            $table->timestamps(); // Timestamps untuk created_at dan updated_at
            $table->softDeletes(); // Fitur soft delete
            $table->foreign('no_pegawai')->references('no_pegawai')->on('pegawai')->onDelete('cascade'); // Foreign key constraint ke tabel pegawai
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan'); // Drop tabel jika rollback migrasi
    }
};
