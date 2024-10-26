<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pegawai';

    // Daftar kolom yang bisa diisi secara massal
    protected $fillable = ['nama', 'email', 'alamat', 'id_role', 'user_id'];

    // Kolom untuk soft delete
    protected $dates = ['deleted_at'];

    /**
     * Relasi ke model Role
     * Setiap Pegawai memiliki satu Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id');
    }

    /**
     * Relasi ke model User
     * Setiap Pegawai terhubung ke satu akun User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
