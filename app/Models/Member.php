<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'no_hp', 'tingkat', 'total_transaksi'];

    const LOYALTY_LEVELS = [
        'bronze' => 'Bronze',
        'silver' => 'Silver',
        'gold' => 'Gold',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'member_id');
    }

    /**
     * Tambahkan transaksi dan cek apakah ada upgrade tier.
     */
    public function addTransaction($nominal)
    {
        // Tambahkan nominal transaksi ke total transaksi
        $this->total_transaksi += $nominal;
        $this->save();

        //   Debugging: Tambahkan log untuk memastikan addTransaction berjalan
        \Log::info("Total transaksi setelah penambahan: {$this->total_transaksi} untuk member ID: {$this->id}");

        // Lakukan pengecekan dan upgrade tingkat jika memenuhi syarat
        $this->upgradeLoyaltyLevel();
    }

    /**
     * Logika untuk upgrade tingkat loyalitas member.
     */
    public function upgradeLoyaltyLevel()
    {
        if ($this->total_transaksi >= 500000 && $this->tingkat !== 'gold') {
            $this->tingkat = 'gold';
            \Log::info("Tingkat loyalitas di-upgrade ke: gold untuk member ID: {$this->id}");
        } elseif ($this->total_transaksi >= 250000 && $this->tingkat !== 'silver') {
            $this->tingkat = 'silver';
            \Log::info("Tingkat loyalitas di-upgrade ke: silver untuk member ID: {$this->id}");
        } elseif ($this->total_transaksi >= 100000 && $this->tingkat !== 'bronze') {
            $this->tingkat = 'bronze';
            \Log::info("Tingkat loyalitas di-upgrade ke: bronze untuk member ID: {$this->id}");
        }

        $this->save(); // Simpan perubahan tingkat
    }
}
