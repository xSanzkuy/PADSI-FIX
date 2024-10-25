<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products'; // Nama tabel di database

    protected $fillable = [
        'nama_produk', // Nama produk
        'stok',        // Stok produk
        'harga',       // Harga produk
        'gambar',      // Gambar produk
    ];

    protected $dates = ['deleted_at']; // Soft delete
}
