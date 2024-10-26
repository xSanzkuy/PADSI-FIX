<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets'; // Nama tabel

    protected $fillable = [
        'email',
        'token',
    ];

    public $timestamps = true; // Gunakan jika Anda ingin menyimpan timestamp
}
