<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['nama', 'no_hp', 'loyalty_level', 'points'];

    // Relasi ke model Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'member_id'); // Pastikan 'member_id' adalah kolom yang sesuai
    }

    public function upgradeLoyaltyLevel()
    {
        if ($this->points >= 1000 && $this->loyalty_level !== 'gold') {
            $this->loyalty_level = 'gold';
        } elseif ($this->points >= 500 && $this->loyalty_level === 'bronze') {
            $this->loyalty_level = 'silver';
        }

        $this->save();
    }
}
