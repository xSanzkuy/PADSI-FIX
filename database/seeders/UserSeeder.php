<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat user admin default
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456789'),
        ]);

        // Membuat user pegawai contoh
        User::create([
            'username' => 'pegawai',
            'email' => 'pegawai@example.com',
            'password' => Hash::make('123456789'),
        ]);
    }
}
