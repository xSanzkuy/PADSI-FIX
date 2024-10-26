<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Kolom ID produk
            $table->string('nama_produk'); // Kolom untuk nama produk
            $table->integer('stok'); // Kolom untuk stok produk
            $table->decimal('harga', 10, 2); // Kolom untuk harga produk
            $table->string('gambar'); // Kolom untuk gambar produk
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->softDeletes(); // Kolom deleted_at untuk soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products'); // Menghapus tabel jika rollback
    }
};
