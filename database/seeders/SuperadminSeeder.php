<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class SuperadminSeeder extends Seeder
{
    public function run()
    {
        // Pastikan role Superadmin ada
        $superadminRole = Role::firstOrCreate(['nama_role' => 'Superadmin']);

        // Buat akun Superadmin
        $superadmin = User::firstOrCreate(
            ['username' => 'superadmin', 'email' => 'superadmin@example.com'],
            ['password' => bcrypt('super123')] // Ganti password sesuai kebutuhan
        );

        // Kaitkan role Superadmin dengan pengguna ini
        $superadmin->roles()->syncWithoutDetaching([$superadminRole->id]);
    }
}
