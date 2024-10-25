<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member; // Pastikan model ini diimpor dengan benar

class MemberSeeder extends Seeder
{
    public function run()
    {
        Member::create([
            'nama' => 'John Doe',
            'no_hp' => '081234567890',
            'tingkat' => 'gold'
        ]);

        Member::create([
            'nama' => 'Jane Smith',
            'no_hp' => '081234567891',
            'tingkat' => 'silver'
        ]);
    }
}
