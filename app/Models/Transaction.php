<?php
    
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Nonaktifkan auto-increment dan set tipe key sebagai string
    public $incrementing = false; // Menonaktifkan auto-increment
    protected $keyType = 'string'; // Mengatur tipe primary key menjadi string

    protected $fillable = [
        'id', 'tanggal', 'pegawai_id', 'telp_pelanggan', 'total_bayar', 'nominal', 'kembalian'
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
