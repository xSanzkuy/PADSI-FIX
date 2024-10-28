<?php
    
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'tanggal', 'pegawai_id', 'telp_pelanggan', 'total_bayar', 'nominal', 'kembalian'
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