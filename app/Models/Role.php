<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles'; // Nama tabel yang digunakan di database
    protected $fillable = ['nama_role'];

    /**
     * Relasi antara Role dan User (Many-to-Many).
     * Setiap role dapat dimiliki oleh banyak pengguna melalui tabel pivot role_user.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }

    /**
     * Relasi antara Role dan Pegawai (One-to-Many).
     * Setiap role mungkin memiliki beberapa pegawai (jika sesuai dengan struktur Anda).
     */
    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'id_role', 'id');
    }
}
