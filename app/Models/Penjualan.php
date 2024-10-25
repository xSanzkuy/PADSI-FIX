<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan'; // Nama tabel di database
    protected $fillable = [
        'id_penjualan', 'total_harga', 'created_at', 'updated_at'
    ];
}
