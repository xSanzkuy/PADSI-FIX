<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Tambahkan setiap role dengan memastikan tidak ada duplikat
        Role::firstOrCreate(['nama_role' => 'Superadmin'], [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Role::firstOrCreate(['nama_role' => 'Owner'], [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Role::firstOrCreate(['nama_role' => 'Pegawai'], [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Role::firstOrCreate(['nama_role' => 'Manager'], [
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
