<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create(['nama_role' => 'Owner']);
        Role::create(['nama_role' => 'Pegawai']);
        Role::create(['nama_role' => 'Manager']);
    }
}
